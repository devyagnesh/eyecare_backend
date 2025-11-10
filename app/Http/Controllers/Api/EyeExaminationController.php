<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreEyeExaminationRequest;
use App\Http\Requests\Api\UpdateEyeExaminationRequest;
use App\Models\EyeExamination;
use App\Models\Store;
use App\Services\EyeExaminationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EyeExaminationController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(
        private EyeExaminationService $eyeExaminationService
    ) {}

    /**
     * Get all eye examinations for the authenticated user's store.
     * 
     * Query Parameters:
     * - paginated (boolean): Enable/disable pagination (default: true)
     * - per_page (integer): Number of items per page when paginated=true (default: 15, max: 100)
     * - customer_id (integer): Filter by customer ID
     * - exam_date_from (date): Filter examinations from this date (YYYY-MM-DD)
     * - exam_date_to (date): Filter examinations up to this date (YYYY-MM-DD)
     * - sort_by (string): Sort field (exam_date, created_at) - default: exam_date
     * - sort_order (string): Sort order (asc, desc) - default: desc
     */
    public function index(Request $request)
    {
        $user = $request->user();
        
        $store = Store::where('user_id', $user->id)->first();

        if (!$store) {
            return response()->json([
                'success' => false,
                'message' => 'Store not found. Please create a store first.',
            ], 404);
        }

        $filters = [
            'customer_id' => $request->get('customer_id'),
            'exam_date_from' => $request->get('exam_date_from'),
            'exam_date_to' => $request->get('exam_date_to'),
            'sort_by' => $request->get('sort_by', 'exam_date'),
            'sort_order' => $request->get('sort_order', 'desc'),
            'paginated' => filter_var($request->get('paginated', true), FILTER_VALIDATE_BOOLEAN),
            'per_page' => $request->get('per_page', 15),
        ];

        $examinations = $this->eyeExaminationService->getExaminations($store, $filters);

        if ($filters['paginated']) {
            $formattedExaminations = $examinations->map(function ($examination) {
                return $this->eyeExaminationService->formatExamination($examination);
            });
            
            return response()->json([
                'success' => true,
                'data' => [
                    'eye_examinations' => $formattedExaminations->values()->all(),
                    'pagination' => [
                        'current_page' => $examinations->currentPage(),
                        'last_page' => $examinations->lastPage(),
                        'per_page' => $examinations->perPage(),
                        'total' => $examinations->total(),
                        'from' => $examinations->firstItem(),
                        'to' => $examinations->lastItem(),
                    ],
                ],
            ], 200);
        } else {
            $formattedExaminations = $examinations->map(function ($examination) {
                return $this->eyeExaminationService->formatExamination($examination);
            });
            
            return response()->json([
                'success' => true,
                'data' => [
                    'eye_examinations' => $formattedExaminations->values()->all(),
                    'total' => $examinations->count(),
                ],
            ], 200);
        }
    }

    /**
     * Create a new eye examination for the authenticated user's store.
     */
    public function store(StoreEyeExaminationRequest $request)
    {
        $user = $request->user();
        
        $store = Store::where('user_id', $user->id)->first();

        if (!$store) {
            return response()->json([
                'success' => false,
                'message' => 'Store not found. Please create a store first.',
            ], 404);
        }

        try {
            $examination = $this->eyeExaminationService->createExamination($store, $request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Eye examination created successfully.',
                'data' => [
                    'eye_examination' => $this->eyeExaminationService->formatExamination($examination),
                ],
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], $e->getCode() ?: 500);
        }
    }

    /**
     * Get a specific eye examination.
     */
    public function show(Request $request, $id)
    {
        $user = $request->user();
        
        $store = Store::where('user_id', $user->id)->first();

        if (!$store) {
            return response()->json([
                'success' => false,
                'message' => 'Store not found. Please create a store first.',
            ], 404);
        }

        $examination = EyeExamination::where('store_id', $store->id)
            ->where('id', $id)
            ->with('customer:id,name,email,phone_number')
            ->first();

        if (!$examination) {
            return response()->json([
                'success' => false,
                'message' => 'Eye examination not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'eye_examination' => $this->eyeExaminationService->formatExamination($examination),
            ],
        ], 200);
    }

    /**
     * Update an eye examination.
     */
    public function update(UpdateEyeExaminationRequest $request, $id)
    {
        $user = $request->user();
        
        $store = Store::where('user_id', $user->id)->first();

        if (!$store) {
            return response()->json([
                'success' => false,
                'message' => 'Store not found. Please create a store first.',
            ], 404);
        }

        $examination = EyeExamination::where('store_id', $store->id)
            ->where('id', $id)
            ->first();

        if (!$examination) {
            return response()->json([
                'success' => false,
                'message' => 'Eye examination not found.',
            ], 404);
        }

        try {
            $examination = $this->eyeExaminationService->updateExamination($examination, $store, $request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Eye examination updated successfully.',
                'data' => [
                    'eye_examination' => $this->eyeExaminationService->formatExamination($examination),
                ],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], $e->getCode() ?: 500);
        }
    }

    /**
     * Delete an eye examination.
     */
    public function destroy(Request $request, $id)
    {
        $user = $request->user();
        
        $store = Store::where('user_id', $user->id)->first();

        if (!$store) {
            return response()->json([
                'success' => false,
                'message' => 'Store not found. Please create a store first.',
            ], 404);
        }

        $examination = EyeExamination::where('store_id', $store->id)
            ->where('id', $id)
            ->first();

        if (!$examination) {
            return response()->json([
                'success' => false,
                'message' => 'Eye examination not found.',
            ], 404);
        }

        try {
            $this->eyeExaminationService->deleteExamination($examination);

            return response()->json([
                'success' => true,
                'message' => 'Eye examination deleted successfully.',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], $e->getCode() ?: 500);
        }
    }

    /**
     * Download PDF for an eye examination (authenticated).
     */
    public function downloadPdf(Request $request, $id)
    {
        $user = $request->user();
        
        $store = Store::where('user_id', $user->id)->first();

        if (!$store) {
            return response()->json([
                'success' => false,
                'message' => 'Store not found. Please create a store first.',
            ], 404);
        }

        $examination = EyeExamination::where('store_id', $store->id)
            ->where('id', $id)
            ->first();

        if (!$examination) {
            return response()->json([
                'success' => false,
                'message' => 'Eye examination not found.',
            ], 404);
        }

        if (!$examination->pdf_path || !Storage::disk('public')->exists($examination->pdf_path)) {
            // Generate PDF if it doesn't exist
            $examination->load(['customer', 'store.user']);
            try {
                $pdfPath = $this->eyeExaminationService->generatePdf(
                    $examination,
                    $store,
                    $store->user,
                    $examination->customer
                );
                $examination->update(['pdf_path' => $pdfPath]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to generate PDF: ' . $e->getMessage(),
                ], 500);
            }
        }

        $filePath = Storage::disk('public')->path($examination->pdf_path);
        $fileName = 'eye-examination-' . $examination->id . '-' . $examination->exam_date->format('Y-m-d') . '.pdf';

        return response()->download($filePath, $fileName, [
            'Content-Type' => 'application/pdf',
        ]);
    }

    /**
     * Public download PDF for an eye examination (no authentication required).
     * Uses signed URL for security.
     */
    public function publicDownload(Request $request, $id)
    {
        $examination = EyeExamination::find($id);

        if (!$examination) {
            abort(404, 'Eye examination not found.');
        }

        if (!$examination->pdf_path || !Storage::disk('public')->exists($examination->pdf_path)) {
            // Generate PDF if it doesn't exist
            $examination->load(['customer', 'store.user']);
            $store = $examination->store;
            $user = $store->user;
            $customer = $examination->customer;
            
            try {
                $pdfPath = $this->eyeExaminationService->generatePdf($examination, $store, $user, $customer);
                $examination->update(['pdf_path' => $pdfPath]);
            } catch (\Exception $e) {
                abort(500, 'Failed to generate PDF: ' . $e->getMessage());
            }
        }

        $filePath = Storage::disk('public')->path($examination->pdf_path);
        $fileName = 'eye-examination-' . $examination->id . '-' . $examination->exam_date->format('Y-m-d') . '.pdf';

        return response()->download($filePath, $fileName, [
            'Content-Type' => 'application/pdf',
        ]);
    }

    /**
     * Get previous prescription date for a customer.
     */
    public function getPreviousPrescriptionDate(Request $request, $customerId)
    {
        $user = $request->user();
        
        $store = Store::where('user_id', $user->id)->first();

        if (!$store) {
            return response()->json([
                'success' => false,
                'message' => 'Store not found. Please create a store first.',
            ], 404);
        }

        try {
            $data = $this->eyeExaminationService->getPreviousPrescriptionDate($store, $customerId);

            return response()->json([
                'success' => true,
                'data' => $data,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], $e->getCode() ?: 500);
        }
    }
}
