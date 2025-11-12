<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePermissionRequest;
use App\Http\Requests\Admin\UpdatePermissionRequest;
use App\Http\Traits\HandlesAjaxResponses;
use App\Http\Traits\HandlesSlugGeneration;
use App\Models\Permission;
use App\Services\PermissionService;
use Illuminate\Http\Request;

/**
 * Permission Controller
 * 
 * Handles admin panel requests for permissions management.
 * 
 * @package App\Http\Controllers\Admin
 */
class PermissionController extends Controller
{
    use HandlesAjaxResponses, HandlesSlugGeneration;

    /**
     * Create a new controller instance.
     *
     * @param PermissionService $permissionService
     */
    public function __construct(
        private PermissionService $permissionService
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
            'module' => $request->get('module'),
            'is_active' => $request->get('is_active'),
            'sort_by' => $request->get('sort_by', 'created_at'),
            'sort_order' => $request->get('sort_order', 'desc'),
        ];

        $permissions = $this->permissionService->getPermissions($filters, false);
        $modules = $this->permissionService->getModules();

        // If AJAX request, return only table HTML
        if ($request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json([
                'html' => view('admin.permissions.index', compact('permissions', 'modules', 'filters'))->render()
            ]);
        }

        return view('admin.permissions.index', compact('permissions', 'modules', 'filters'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.permissions.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StorePermissionRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StorePermissionRequest $request)
    {
        try {
            $validated = $request->validated();
            $validated = $this->generateSlug($validated);
            $validated = $this->setActiveFlag($validated, $request);

            $this->permissionService->createPermission($validated);

            return $this->handleResponse($request, 'Permission created successfully.', 'admin.permissions.index');
        } catch (\Exception $e) {
            return $this->handleErrorResponse($request, $e->getMessage(), 'admin.permissions.create');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Permission $permission)
    {
        $permission->load('roles');
        return view('admin.permissions.show', compact('permission'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Permission $permission)
    {
        return view('admin.permissions.edit', compact('permission'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdatePermissionRequest $request
     * @param Permission $permission
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdatePermissionRequest $request, Permission $permission)
    {
        try {
            $validated = $request->validated();
            $validated = $this->generateSlug($validated);
            $validated = $this->setActiveFlag($validated, $request);

            $this->permissionService->updatePermission($permission->id, $validated);

            return $this->handleResponse($request, 'Permission updated successfully.', 'admin.permissions.index');
        } catch (\Exception $e) {
            return $this->handleErrorResponse($request, $e->getMessage(), 'admin.permissions.edit', ['permission' => $permission]);
        }
    }

    /**
     * Remove the specified resource from storage (soft delete).
     *
     * @param Request $request
     * @param Permission $permission
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, Permission $permission)
    {
        try {
            $this->permissionService->deletePermission($permission->id);
            return $this->handleResponse($request, 'Permission deleted successfully.', 'admin.permissions.index');
        } catch (\Exception $e) {
            return $this->handleErrorResponse($request, $e->getMessage(), 'admin.permissions.index');
        }
    }

    /**
     * Restore a soft-deleted permission.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restore(Request $request, int $id)
    {
        try {
            $this->permissionService->restorePermission($id);
            return $this->handleResponse($request, 'Permission restored successfully.', 'admin.permissions.index');
        } catch (\Exception $e) {
            return $this->handleErrorResponse($request, $e->getMessage(), 'admin.permissions.index');
        }
    }

    /**
     * Permanently delete a permission.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function forceDelete(Request $request, int $id)
    {
        try {
            $this->permissionService->forceDeletePermission($id);
            return $this->handleResponse($request, 'Permission permanently deleted.', 'admin.permissions.index');
        } catch (\Exception $e) {
            return $this->handleErrorResponse($request, $e->getMessage(), 'admin.permissions.index');
        }
    }
}