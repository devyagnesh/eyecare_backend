<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateStoreRequest;
use App\Http\Traits\HandlesAjaxResponses;
use App\Models\Store;
use App\Services\StoreService;
use Illuminate\Http\Request;

/**
 * Store Controller
 * 
 * Handles admin panel requests for stores management.
 * 
 * @package App\Http\Controllers\Admin
 */
class StoreController extends Controller
{
    use HandlesAjaxResponses;

    /**
     * Create a new controller instance.
     *
     * @param StoreService $storeService
     */
    public function __construct(
        private StoreService $storeService
    ) {}

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $filters = [
            'search' => $request->get('search'),
            'is_active' => $request->get('is_active'),
            'user_id' => $request->get('user_id'),
            'sort_by' => $request->get('sort_by', 'created_at'),
            'sort_order' => $request->get('sort_order', 'desc'),
        ];

        $stores = $this->storeService->getStores($filters, true);

        // If AJAX request, return only table HTML
        if ($request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json([
                'html' => view('admin.stores.index', compact('stores', 'filters'))->render()
            ]);
        }

        return view('admin.stores.index', compact('stores', 'filters'));
    }

    /**
     * Display the specified resource.
     *
     * @param Store $store
     * @return \Illuminate\View\View
     */
    public function show(Store $store)
    {
        $store->load(['user'])
            ->loadCount(['customers', 'eyeExaminations', 'orders']);
        return view('admin.stores.show', compact('store'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Store $store
     * @return \Illuminate\View\View
     */
    public function edit(Store $store)
    {
        $store->load('user');
        return view('admin.stores.edit', compact('store'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateStoreRequest $request
     * @param Store $store
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateStoreRequest $request, Store $store)
    {
        try {
            $this->storeService->updateStore($store->id, $request->validated());
            return $this->handleResponse($request, 'Store updated successfully.', 'admin.stores.index');
        } catch (\Exception $e) {
            return $this->handleErrorResponse($request, $e->getMessage(), 'admin.stores.edit', ['store' => $store]);
        }
    }

    /**
     * Toggle store active status.
     *
     * @param Request $request
     * @param Store $store
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toggleStatus(Request $request, Store $store)
    {
        try {
            $updatedStore = $this->storeService->toggleStoreStatus($store->id);
            $status = $updatedStore->is_active ? 'activated' : 'deactivated';
            return $this->handleResponse($request, "Store {$status} successfully.", 'admin.stores.index');
        } catch (\Exception $e) {
            return $this->handleErrorResponse($request, $e->getMessage(), 'admin.stores.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param Store $store
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, Store $store)
    {
        try {
            $this->storeService->deleteStore($store->id);
            return $this->handleResponse($request, 'Store deleted successfully.', 'admin.stores.index');
        } catch (\Exception $e) {
            return $this->handleErrorResponse($request, $e->getMessage(), 'admin.stores.index');
        }
    }
}
