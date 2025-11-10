<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\EyeExamination;
use App\Models\Store;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf as DomPDF;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\DB;

class EyeExaminationService
{
    /**
     * Get all eye examinations for a store with filters.
     *
     * @param Store $store
     * @param array $filters
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Collection
     */
    public function getExaminations(Store $store, array $filters = [])
    {
        $query = EyeExamination::where('store_id', $store->id)
            ->with('customer:id,name,email,phone_number');

        // Filter by customer
        if (!empty($filters['customer_id'])) {
            $query->where('customer_id', $filters['customer_id']);
        }

        // Filter by date range
        if (!empty($filters['exam_date_from'])) {
            $query->whereDate('exam_date', '>=', $filters['exam_date_from']);
        }
        if (!empty($filters['exam_date_to'])) {
            $query->whereDate('exam_date', '<=', $filters['exam_date_to']);
        }

        // Sorting
        $sortBy = $filters['sort_by'] ?? 'exam_date';
        $sortOrder = $filters['sort_order'] ?? 'desc';
        
        $allowedSortFields = ['exam_date', 'created_at'];
        if (!in_array($sortBy, $allowedSortFields)) {
            $sortBy = 'exam_date';
        }
        
        $sortOrder = strtolower($sortOrder);
        if (!in_array($sortOrder, ['asc', 'desc'])) {
            $sortOrder = 'desc';
        }
        
        $query->orderBy($sortBy, $sortOrder);

        // Pagination toggle
        $paginated = $filters['paginated'] ?? true;
        
        if ($paginated) {
            $perPage = min(max((int) ($filters['per_page'] ?? 15), 1), 100);
            return $query->paginate($perPage);
        }
        
        return $query->get();
    }

    /**
     * Create a new eye examination.
     *
     * @param Store $store
     * @param array $data
     * @return EyeExamination
     * @throws \Exception
     */
    public function createExamination(Store $store, array $data): EyeExamination
    {
        // Verify store_id matches the authenticated user's store
        if (isset($data['store_id']) && (int)$data['store_id'] !== $store->id) {
            throw new \Exception('Store ID does not match your store.', 403);
        }

        // Verify customer belongs to the store
        $customer = Customer::find($data['customer_id']);

        if (!$customer) {
            throw new \Exception('Customer not found.', 404);
        }

        if ((int)$customer->store_id !== (int)$store->id) {
            throw new \Exception('Customer does not belong to your store.', 403);
        }

        try {
            DB::beginTransaction();

            $examination = EyeExamination::create([
                'customer_id' => $data['customer_id'],
                'store_id' => $store->id,
                'exam_date' => $data['exam_date'],
                'chief_complaint' => $data['chief_complaint'] ?? null,
                'old_rx_date' => $data['old_rx_date'] ?? null,
                'od_va_unaided' => $data['od_va_unaided'] ?? null,
                'os_va_unaided' => $data['os_va_unaided'] ?? null,
                'od_sphere' => $data['od_sphere'] ?? null,
                'od_cylinder' => $data['od_cylinder'] ?? null,
                'od_axis' => $data['od_axis'] ?? null,
                'os_sphere' => $data['os_sphere'] ?? null,
                'os_cylinder' => $data['os_cylinder'] ?? null,
                'os_axis' => $data['os_axis'] ?? null,
                'add_power' => $data['add_power'] ?? null,
                'pd_distance' => $data['pd_distance'] ?? null,
                'pd_near' => $data['pd_near'] ?? null,
                'od_bcva' => $data['od_bcva'] ?? null,
                'os_bcva' => $data['os_bcva'] ?? null,
                'iop_od' => $data['iop_od'] ?? null,
                'iop_os' => $data['iop_os'] ?? null,
                'fundus_notes' => $data['fundus_notes'] ?? null,
                'diagnosis' => $data['diagnosis'] ?? null,
                'management_plan' => $data['management_plan'] ?? null,
                'next_recall_date' => $data['next_recall_date'] ?? null,
            ]);

            // Load relationships for PDF generation
            $examination->load(['customer', 'store.user']);

            // Generate PDF
            $pdfPath = $this->generatePdf(
                $examination,
                $store,
                $store->user,
                $customer
            );

            // Update examination with PDF path
            $examination->update(['pdf_path' => $pdfPath]);

            DB::commit();

            Log::info('Eye examination created', [
                'examination_id' => $examination->id,
                'store_id' => $store->id,
                'customer_id' => $customer->id,
            ]);

            return $examination->fresh();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create eye examination', [
                'error' => $e->getMessage(),
                'store_id' => $store->id,
                'data' => $data,
            ]);
            throw $e;
        }
    }

    /**
     * Update an eye examination.
     *
     * @param EyeExamination $examination
     * @param Store $store
     * @param array $data
     * @return EyeExamination
     * @throws \Exception
     */
    public function updateExamination(EyeExamination $examination, Store $store, array $data): EyeExamination
    {
        // Verify store_id matches the authenticated user's store if provided
        if (isset($data['store_id']) && (int)$data['store_id'] !== $store->id) {
            throw new \Exception('Store ID does not match your store.', 403);
        }

        // If customer_id is being updated, verify it belongs to the store
        if (isset($data['customer_id']) && $data['customer_id'] != $examination->customer_id) {
            $customer = Customer::find($data['customer_id']);

            if (!$customer) {
                throw new \Exception('Customer not found.', 404);
            }

            if ($customer->store_id !== $store->id) {
                throw new \Exception('Customer does not belong to your store.', 403);
            }
        }

        try {
            DB::beginTransaction();

            $examination->update($data);

            // Regenerate PDF if key fields changed
            $pdfRegenerateFields = ['exam_date', 'chief_complaint', 'diagnosis', 'od_sphere', 'os_sphere'];
            $shouldRegenerate = false;
            
            foreach ($pdfRegenerateFields as $field) {
                if (isset($data[$field])) {
                    $shouldRegenerate = true;
                    break;
                }
            }

            if ($shouldRegenerate) {
                $examination->load(['customer', 'store.user']);
                $pdfPath = $this->generatePdf(
                    $examination,
                    $store,
                    $store->user,
                    $examination->customer
                );
                $examination->update(['pdf_path' => $pdfPath]);
            }

            DB::commit();

            Log::info('Eye examination updated', [
                'examination_id' => $examination->id,
                'store_id' => $store->id,
            ]);

            return $examination->fresh();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update eye examination', [
                'error' => $e->getMessage(),
                'examination_id' => $examination->id,
                'data' => $data,
            ]);
            throw $e;
        }
    }

    /**
     * Delete an eye examination.
     *
     * @param EyeExamination $examination
     * @return bool
     * @throws \Exception
     */
    public function deleteExamination(EyeExamination $examination): bool
    {
        try {
            // Delete PDF file if exists
            if ($examination->pdf_path && Storage::disk('public')->exists($examination->pdf_path)) {
                Storage::disk('public')->delete($examination->pdf_path);
            }

            $examinationId = $examination->id;
            $storeId = $examination->store_id;

            $examination->delete();

            Log::info('Eye examination deleted', [
                'examination_id' => $examinationId,
                'store_id' => $storeId,
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to delete eye examination', [
                'error' => $e->getMessage(),
                'examination_id' => $examination->id,
            ]);
            throw $e;
        }
    }

    /**
     * Generate PDF for eye examination.
     *
     * @param EyeExamination $examination
     * @param Store $store
     * @param User $user
     * @param Customer $customer
     * @return string
     * @throws \Exception
     */
    public function generatePdf(EyeExamination $examination, Store $store, User $user, Customer $customer): string
    {
        // Validate required data for PDF generation
        $this->validatePdfData($examination, $store, $user, $customer);

        try {
            // Generate PDF using the view
            $pdf = DomPDF::loadView('pdf.eye-examination', [
                'examination' => $examination,
                'store' => $store,
                'user' => $user,
                'customer' => $customer,
            ]);

            // Set PDF options
            $pdf->setPaper('A4', 'portrait');
            $pdf->setOption('enable-local-file-access', true);

            // Generate filename
            $filename = 'eye-examinations/' . $examination->id . '/examination-' . $examination->id . '-' . $examination->exam_date->format('Y-m-d') . '.pdf';
            
            // Store PDF in public disk
            Storage::disk('public')->put($filename, $pdf->output());

            Log::info('PDF generated successfully', [
                'examination_id' => $examination->id,
                'pdf_path' => $filename,
            ]);

            return $filename;
        } catch (\Exception $e) {
            Log::error('Failed to generate PDF', [
                'error' => $e->getMessage(),
                'examination_id' => $examination->id,
            ]);
            throw new \Exception('Failed to generate PDF: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Validate data required for PDF generation.
     *
     * @param EyeExamination $examination
     * @param Store $store
     * @param User $user
     * @param Customer $customer
     * @return void
     * @throws \Exception
     */
    private function validatePdfData(EyeExamination $examination, Store $store, User $user, Customer $customer): void
    {
        if (!$examination) {
            throw new \Exception('Examination data is required for PDF generation.', 400);
        }

        if (!$store) {
            throw new \Exception('Store information is required for PDF generation.', 400);
        }

        if (!$store->name) {
            throw new \Exception('Store name is required for PDF generation.', 400);
        }

        if (!$user) {
            throw new \Exception('Doctor information is required for PDF generation.', 400);
        }

        if (!$user->name) {
            throw new \Exception('Doctor name is required for PDF generation.', 400);
        }

        if (!$customer) {
            throw new \Exception('Customer information is required for PDF generation.', 400);
        }

        if (!$customer->name) {
            throw new \Exception('Customer name is required for PDF generation.', 400);
        }

        if (!$examination->exam_date) {
            throw new \Exception('Examination date is required for PDF generation.', 400);
        }

        if (!$examination->diagnosis) {
            throw new \Exception('Diagnosis is required for PDF generation.', 400);
        }
    }

    /**
     * Get previous prescription date for a customer.
     *
     * @param Store $store
     * @param int $customerId
     * @return array
     */
    public function getPreviousPrescriptionDate(Store $store, int $customerId): array
    {
        // Verify customer belongs to the store
        $customer = Customer::find($customerId);

        if (!$customer) {
            throw new \Exception('Customer not found.', 404);
        }

        if ((int)$customer->store_id !== (int)$store->id) {
            throw new \Exception('Customer does not belong to your store.', 403);
        }

        // Get the most recent examination for this customer
        $latestExamination = EyeExamination::where('store_id', $store->id)
            ->where('customer_id', $customerId)
            ->orderBy('exam_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$latestExamination) {
            return [
                'customer_id' => $customerId,
                'customer_name' => $customer->name,
                'has_previous_examination' => false,
                'last_exam_date' => null,
                'old_rx_date' => null,
                'message' => 'No previous examinations found for this customer.',
            ];
        }

        return [
            'customer_id' => $customerId,
            'customer_name' => $customer->name,
            'has_previous_examination' => true,
            'last_exam_date' => $latestExamination->exam_date->format('Y-m-d'),
            'last_exam_date_formatted' => $latestExamination->exam_date->format('F d, Y'),
            'old_rx_date' => $latestExamination->old_rx_date ? $latestExamination->old_rx_date->format('Y-m-d') : null,
            'old_rx_date_formatted' => $latestExamination->old_rx_date ? $latestExamination->old_rx_date->format('F d, Y') : null,
            'last_examination_id' => $latestExamination->id,
            'days_since_last_exam' => now()->diffInDays($latestExamination->exam_date),
        ];
    }

    /**
     * Format eye examination data for API response.
     *
     * @param EyeExamination $examination
     * @return array
     */
    public function formatExamination(EyeExamination $examination): array
    {
        return [
            'id' => $examination->id,
            'customer_id' => $examination->customer_id,
            'customer' => $examination->customer ? [
                'id' => $examination->customer->id,
                'name' => $examination->customer->name,
                'email' => $examination->customer->email,
                'phone_number' => $examination->customer->phone_number,
            ] : null,
            'store_id' => $examination->store_id,
            'exam_date' => $examination->exam_date->format('Y-m-d'),
            'chief_complaint' => $examination->chief_complaint,
            'old_rx_date' => $examination->old_rx_date ? $examination->old_rx_date->format('Y-m-d') : null,
            'od_va_unaided' => $examination->od_va_unaided,
            'os_va_unaided' => $examination->os_va_unaided,
            'od_sphere' => $examination->od_sphere,
            'od_cylinder' => $examination->od_cylinder,
            'od_axis' => $examination->od_axis,
            'os_sphere' => $examination->os_sphere,
            'os_cylinder' => $examination->os_cylinder,
            'os_axis' => $examination->os_axis,
            'add_power' => $examination->add_power,
            'pd_distance' => $examination->pd_distance,
            'pd_near' => $examination->pd_near,
            'od_bcva' => $examination->od_bcva,
            'os_bcva' => $examination->os_bcva,
            'iop_od' => $examination->iop_od,
            'iop_os' => $examination->iop_os,
            'fundus_notes' => $examination->fundus_notes,
            'diagnosis' => $examination->diagnosis,
            'management_plan' => $examination->management_plan,
            'next_recall_date' => $examination->next_recall_date ? $examination->next_recall_date->format('Y-m-d') : null,
            'pdf_download_url' => $examination->pdf_path ? url('api/eye-examinations/' . $examination->id . '/download-pdf') : null,
            'pdf_public_download_url' => URL::signedRoute('eye-examination.public-download', ['id' => $examination->id]),
            'has_pdf' => !empty($examination->pdf_path) && Storage::disk('public')->exists($examination->pdf_path),
            'created_at' => $examination->created_at->toIso8601String(),
            'updated_at' => $examination->updated_at->toIso8601String(),
        ];
    }
}

