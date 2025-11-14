<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Store;
use App\Models\Customer;
use App\Models\EyeExamination;
use App\Models\User;
use Mpdf\Mpdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class OrderService
{
    /**
     * Generate unique invoice number.
     *
     * @param Store $store
     * @return string
     */
    public function generateInvoiceNumber(Store $store): string
    {
        $prefix = 'INV-' . strtoupper(substr($store->name, 0, 3));
        $year = date('Y');
        $month = date('m');
        
        // Get the last order number for this store
        $lastOrder = Order::where('store_id', $store->id)
            ->where('invoice_number', 'like', $prefix . '-' . $year . $month . '%')
            ->orderBy('invoice_number', 'desc')
            ->first();
        
        if ($lastOrder) {
            $lastNumber = (int) substr($lastOrder->invoice_number, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        return $prefix . '-' . $year . $month . '-' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Create a new order.
     *
     * @param Store $store
     * @param array $data
     * @return Order
     * @throws \Exception
     */
    public function createOrder(Store $store, array $data): Order
    {
        try {
            DB::beginTransaction();

            // Validate customer belongs to store
            $customer = Customer::where('id', $data['customer_id'])
                ->where('store_id', $store->id)
                ->firstOrFail();

            // Validate eye examination if provided
            $eyeExamination = null;
            if (!empty($data['eye_examination_id'])) {
                $eyeExamination = EyeExamination::where('id', $data['eye_examination_id'])
                    ->where('store_id', $store->id)
                    ->where('customer_id', $customer->id)
                    ->firstOrFail();
            }

            // Generate invoice number
            $invoiceNumber = $this->generateInvoiceNumber($store);

            // Handle frame photo upload
            $framePhotoPath = null;
            if (!empty($data['frame_photo'])) {
                $framePhotoPath = $this->storeFramePhoto($data['frame_photo'], $store->id, $invoiceNumber);
            }

            // Create order
            $order = Order::create([
                'customer_id' => $customer->id,
                'store_id' => $store->id,
                'eye_examination_id' => $eyeExamination?->id,
                'invoice_number' => $invoiceNumber,
                'frame_photo' => $framePhotoPath,
                'glass_details' => $data['glass_details'] ?? null,
                'total_price' => $data['total_price'],
                'expected_completion_date' => $data['expected_completion_date'],
                'status' => $data['status'] ?? 'pending',
                'notes' => $data['notes'] ?? null,
            ]);

            // Load relationships
            $order->load(['customer', 'store.user', 'eyeExamination']);

            // Generate invoice PDF
            $pdfPath = $this->generateInvoicePdf($order, $store, $store->user, $customer, $eyeExamination);

            // Update order with PDF path
            $order->update(['invoice_pdf_path' => $pdfPath]);

            DB::commit();

            Log::info('Order created successfully', [
                'order_id' => $order->id,
                'invoice_number' => $invoiceNumber,
                'store_id' => $store->id,
                'customer_id' => $customer->id,
            ]);

            return $order->fresh(['customer', 'store.user', 'eyeExamination']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create order', [
                'error' => $e->getMessage(),
                'store_id' => $store->id,
                'data' => $data,
            ]);
            throw $e;
        }
    }

    /**
     * Store frame photo.
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param int $storeId
     * @param string $invoiceNumber
     * @return string
     */
    private function storeFramePhoto($file, int $storeId, string $invoiceNumber): string
    {
        $filename = 'orders/' . $storeId . '/' . $invoiceNumber . '/frame-' . time() . '.' . $file->getClientOriginalExtension();
        
        $path = Storage::disk('public')->putFileAs(
            dirname($filename),
            $file,
            basename($filename)
        );

        return $path;
    }

    /**
     * Generate invoice PDF.
     *
     * @param Order $order
     * @param Store $store
     * @param User $user
     * @param Customer $customer
     * @param EyeExamination|null $eyeExamination
     * @return string
     * @throws \Exception
     */
    public function generateInvoicePdf(Order $order, Store $store, User $user, Customer $customer, ?EyeExamination $eyeExamination = null): string
    {
        try {
            // Render the view to HTML
            $html = view('pdf.invoice', [
                'order' => $order,
                'store' => $store,
                'user' => $user,
                'customer' => $customer,
                'eyeExamination' => $eyeExamination,
            ])->render();

            // Validate HTML is not empty
            if (empty(trim($html))) {
                throw new \Exception('Generated HTML is empty.', 500);
            }

            // Generate filename
            $filename = 'invoices/' . $store->id . '/' . $order->invoice_number . '/invoice-' . $order->invoice_number . '.pdf';
            
            // Get full path for storage
            $fullPath = Storage::disk('public')->path($filename);
            
            // Ensure directory exists
            $directory = dirname($fullPath);
            if (!is_dir($directory)) {
                mkdir($directory, 0755, true);
            }

            // Ensure temp directory exists for mPDF
            $tempDir = storage_path('app/temp');
            if (!is_dir($tempDir)) {
                mkdir($tempDir, 0755, true);
            }

            // Configure mPDF for A4 format with margins
            $mpdf = new Mpdf([
                'mode' => 'utf-8',
                'format' => 'A4',
                'orientation' => 'P',
                'margin_left' => 10,
                'margin_right' => 10,
                'margin_top' => 10,
                'margin_bottom' => 10,
                'margin_header' => 0,
                'margin_footer' => 0,
                'tempDir' => $tempDir,
                'default_font_size' => 10,
                'default_font' => 'dejavusans',
                'autoScriptToLang' => true,
                'autoLangToFont' => true,
            ]);

            // Clean HTML
            $html = mb_convert_encoding($html, 'UTF-8', 'UTF-8');
            if (substr($html, 0, 3) === "\xEF\xBB\xBF") {
                $html = substr($html, 3);
            }
            
            // Write HTML content
            $mpdf->WriteHTML($html, 0);
            
            // Output PDF as string
            $pdfContent = $mpdf->Output('', 'S');
            
            // Validate PDF content
            if (empty($pdfContent)) {
                throw new \Exception('Generated PDF is empty.', 500);
            }
            
            // Store PDF in public disk
            Storage::disk('public')->put($filename, $pdfContent);

            Log::info('Invoice PDF generated successfully', [
                'order_id' => $order->id,
                'invoice_number' => $order->invoice_number,
                'pdf_path' => $filename,
            ]);

            return $filename;
        } catch (\Exception $e) {
            Log::error('Failed to generate invoice PDF', [
                'error' => $e->getMessage(),
                'order_id' => $order->id,
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

    /**
     * Get all orders for a store with filters.
     *
     * @param Store $store
     * @param array $filters
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Collection
     */
    public function getOrders(Store $store, array $filters = [])
    {
        $query = Order::where('store_id', $store->id)
            ->with(['customer:id,name,email,phone_number', 'eyeExamination:id,exam_date']);

        // Filter by customer
        if (!empty($filters['customer_id'])) {
            $query->where('customer_id', $filters['customer_id']);
        }

        // Filter by status
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // Filter by date range
        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }
        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        // Sorting
        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortOrder = $filters['sort_order'] ?? 'desc';
        
        $allowedSortFields = ['created_at', 'expected_completion_date', 'total_price', 'status'];
        if (!in_array($sortBy, $allowedSortFields)) {
            $sortBy = 'created_at';
        }
        
        $query->orderBy($sortBy, $sortOrder);

        // Pagination
        if ($filters['paginated'] ?? true) {
            $perPage = min($filters['per_page'] ?? 15, 100);
            return $query->paginate($perPage);
        }

        return $query->get();
    }

    /**
     * Format order for API response.
     *
     * @param Order $order
     * @return array
     */
    public function formatOrder(Order $order): array
    {
        // Helper to ensure full URL
        $getFullUrl = function ($path) {
            if (!$path) {
                return null;
            }
            $url = Storage::url($path);
            // If URL is already absolute, return as-is; otherwise make it absolute
            return (str_starts_with($url, 'http://') || str_starts_with($url, 'https://')) 
                ? $url 
                : url($url);
        };

        return [
            'id' => $order->id,
            'invoice_number' => $order->invoice_number,
            'customer' => [
                'id' => $order->customer->id,
                'name' => $order->customer->name,
                'email' => $order->customer->email,
                'phone_number' => $order->customer->phone_number,
            ],
            'eye_examination' => $order->eyeExamination ? [
                'id' => $order->eyeExamination->id,
                'exam_date' => $order->eyeExamination->exam_date->format('Y-m-d'),
            ] : null,
            'frame_photo' => $getFullUrl($order->frame_photo),
            'glass_details' => $order->glass_details,
            'total_price' => (float) $order->total_price,
            'expected_completion_date' => $order->expected_completion_date->format('Y-m-d'),
            'status' => $order->status,
            'invoice_pdf_url' => $getFullUrl($order->invoice_pdf_path),
            'notes' => $order->notes,
            'created_at' => $order->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $order->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}

