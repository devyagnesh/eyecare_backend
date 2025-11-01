<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\EyeExamination;
use App\Models\Store;
use Illuminate\Http\Request;

class EyeExaminationController extends Controller
{
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

        $query = EyeExamination::where('store_id', $store->id)
            ->with('customer:id,name,email,phone_number');

        // Filter by customer
        if ($request->has('customer_id') && !empty($request->customer_id)) {
            $query->where('customer_id', $request->customer_id);
        }

        // Filter by date range
        if ($request->has('exam_date_from')) {
            $query->whereDate('exam_date', '>=', $request->exam_date_from);
        }
        if ($request->has('exam_date_to')) {
            $query->whereDate('exam_date', '<=', $request->exam_date_to);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'exam_date');
        $sortOrder = $request->get('sort_order', 'desc');
        
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
        $paginated = filter_var($request->get('paginated', true), FILTER_VALIDATE_BOOLEAN);
        
        if ($paginated) {
            $perPage = (int) $request->get('per_page', 15);
            $perPage = min(max($perPage, 1), 100);
            
            $examinations = $query->paginate($perPage);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'eye_examinations' => $examinations->items(),
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
            $examinations = $query->get();
            
            return response()->json([
                'success' => true,
                'data' => [
                    'eye_examinations' => $examinations,
                    'total' => $examinations->count(),
                ],
            ], 200);
        }
    }

    /**
     * Create a new eye examination for the authenticated user's store.
     */
    public function store(Request $request)
    {
        $user = $request->user();
        
        $store = Store::where('user_id', $user->id)->first();

        if (!$store) {
            return response()->json([
                'success' => false,
                'message' => 'Store not found. Please create a store first.',
            ], 404);
        }

        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'exam_date' => 'required|date',
            'chief_complaint' => 'nullable|string',
            'old_rx_date' => 'nullable|date',
            'od_va_unaided' => 'nullable|string|max:255',
            'os_va_unaided' => 'nullable|string|max:255',
            'od_sphere' => 'nullable|numeric',
            'od_cylinder' => 'nullable|numeric',
            'od_axis' => 'nullable|integer|min:0|max:180',
            'os_sphere' => 'nullable|numeric',
            'os_cylinder' => 'nullable|numeric',
            'os_axis' => 'nullable|integer|min:0|max:180',
            'add_power' => 'nullable|numeric',
            'pd_distance' => 'nullable|numeric|min:0',
            'pd_near' => 'nullable|numeric|min:0',
            'od_bcva' => 'nullable|string|max:255',
            'os_bcva' => 'nullable|string|max:255',
            'iop_od' => 'nullable|integer|min:0',
            'iop_os' => 'nullable|integer|min:0',
            'fundus_notes' => 'nullable|string',
            'diagnosis' => 'nullable|string',
            'management_plan' => 'nullable|string',
            'next_recall_date' => 'nullable|date',
        ]);

        // Verify customer belongs to the store
        $customer = Customer::where('id', $validated['customer_id'])
            ->where('store_id', $store->id)
            ->first();

        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'Customer not found or does not belong to your store.',
            ], 404);
        }

        $examination = EyeExamination::create([
            'customer_id' => $validated['customer_id'],
            'store_id' => $store->id,
            'exam_date' => $validated['exam_date'],
            'chief_complaint' => $validated['chief_complaint'] ?? null,
            'old_rx_date' => $validated['old_rx_date'] ?? null,
            'od_va_unaided' => $validated['od_va_unaided'] ?? null,
            'os_va_unaided' => $validated['os_va_unaided'] ?? null,
            'od_sphere' => $validated['od_sphere'] ?? null,
            'od_cylinder' => $validated['od_cylinder'] ?? null,
            'od_axis' => $validated['od_axis'] ?? null,
            'os_sphere' => $validated['os_sphere'] ?? null,
            'os_cylinder' => $validated['os_cylinder'] ?? null,
            'os_axis' => $validated['os_axis'] ?? null,
            'add_power' => $validated['add_power'] ?? null,
            'pd_distance' => $validated['pd_distance'] ?? null,
            'pd_near' => $validated['pd_near'] ?? null,
            'od_bcva' => $validated['od_bcva'] ?? null,
            'os_bcva' => $validated['os_bcva'] ?? null,
            'iop_od' => $validated['iop_od'] ?? null,
            'iop_os' => $validated['iop_os'] ?? null,
            'fundus_notes' => $validated['fundus_notes'] ?? null,
            'diagnosis' => $validated['diagnosis'] ?? null,
            'management_plan' => $validated['management_plan'] ?? null,
            'next_recall_date' => $validated['next_recall_date'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Eye examination created successfully.',
            'data' => [
                'eye_examination' => $this->formatExamination($examination),
            ],
        ], 201);
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
                'eye_examination' => $this->formatExamination($examination),
            ],
        ], 200);
    }

    /**
     * Update an eye examination.
     */
    public function update(Request $request, $id)
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

        $validated = $request->validate([
            'customer_id' => 'sometimes|required|exists:customers,id',
            'exam_date' => 'sometimes|required|date',
            'chief_complaint' => 'nullable|string',
            'old_rx_date' => 'nullable|date',
            'od_va_unaided' => 'nullable|string|max:255',
            'os_va_unaided' => 'nullable|string|max:255',
            'od_sphere' => 'nullable|numeric',
            'od_cylinder' => 'nullable|numeric',
            'od_axis' => 'nullable|integer|min:0|max:180',
            'os_sphere' => 'nullable|numeric',
            'os_cylinder' => 'nullable|numeric',
            'os_axis' => 'nullable|integer|min:0|max:180',
            'add_power' => 'nullable|numeric',
            'pd_distance' => 'nullable|numeric|min:0',
            'pd_near' => 'nullable|numeric|min:0',
            'od_bcva' => 'nullable|string|max:255',
            'os_bcva' => 'nullable|string|max:255',
            'iop_od' => 'nullable|integer|min:0',
            'iop_os' => 'nullable|integer|min:0',
            'fundus_notes' => 'nullable|string',
            'diagnosis' => 'nullable|string',
            'management_plan' => 'nullable|string',
            'next_recall_date' => 'nullable|date',
        ]);

        // If customer_id is being updated, verify it belongs to the store
        if (isset($validated['customer_id']) && $validated['customer_id'] != $examination->customer_id) {
            $customer = Customer::where('id', $validated['customer_id'])
                ->where('store_id', $store->id)
                ->first();

            if (!$customer) {
                return response()->json([
                    'success' => false,
                    'message' => 'Customer not found or does not belong to your store.',
                ], 404);
            }
        }

        $examination->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Eye examination updated successfully.',
            'data' => [
                'eye_examination' => $this->formatExamination($examination->fresh()),
            ],
        ], 200);
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

        $examination->delete();

        return response()->json([
            'success' => true,
            'message' => 'Eye examination deleted successfully.',
        ], 200);
    }

    /**
     * Format eye examination data for API response.
     */
    private function formatExamination($examination)
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
            'created_at' => $examination->created_at->toIso8601String(),
            'updated_at' => $examination->updated_at->toIso8601String(),
        ];
    }
}
