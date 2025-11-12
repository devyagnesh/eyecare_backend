<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreRoleRequest;
use App\Http\Requests\Admin\UpdateRoleRequest;
use App\Http\Traits\HandlesAjaxResponses;
use App\Http\Traits\HandlesSlugGeneration;
use App\Models\Role;
use App\Models\Permission;
use App\Services\RoleService;
use Illuminate\Http\Request;

/**
 * Role Controller
 * 
 * Handles admin panel requests for roles management.
 * 
 * @package App\Http\Controllers\Admin
 */
class RoleController extends Controller
{
    use HandlesAjaxResponses, HandlesSlugGeneration;

    /**
     * Create a new controller instance.
     *
     * @param RoleService $roleService
     */
    public function __construct(
        private RoleService $roleService
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
            'sort_by' => $request->get('sort_by', 'created_at'),
            'sort_order' => $request->get('sort_order', 'desc'),
        ];

        $roles = $this->roleService->getRoles($filters, false);
        
        // If AJAX request, return only table HTML
        if ($request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json([
                'html' => view('admin.roles.index', compact('roles', 'filters'))->render()
            ]);
        }
        
        return view('admin.roles.index', compact('roles', 'filters'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permissions = Permission::active()->orderBy('module')->orderBy('name')->get()->groupBy('module');
        return view('admin.roles.create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreRoleRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreRoleRequest $request)
    {
        try {
            $validated = $request->validated();
            $validated = $this->generateSlug($validated);
            $validated = $this->setActiveFlag($validated, $request);

            $role = $this->roleService->createRole($validated);

            if ($request->has('permissions')) {
                $role->permissions()->sync($request->permissions);
            }

            return $this->handleResponse($request, 'Role created successfully.', 'admin.roles.index');
        } catch (\Exception $e) {
            return $this->handleErrorResponse($request, $e->getMessage(), 'admin.roles.create');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        $role->load(['permissions', 'users']);
        return view('admin.roles.show', compact('role'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        $permissions = Permission::active()->orderBy('module')->orderBy('name')->get()->groupBy('module');
        $role->load('permissions');
        return view('admin.roles.edit', compact('role', 'permissions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateRoleRequest $request
     * @param Role $role
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateRoleRequest $request, Role $role)
    {
        try {
            $validated = $request->validated();
            $validated = $this->generateSlug($validated);
            $validated = $this->setActiveFlag($validated, $request);

            $this->roleService->updateRole($role->id, $validated);

            if ($request->has('permissions')) {
                $role->permissions()->sync($request->permissions);
            } else {
                $role->permissions()->detach();
            }

            return $this->handleResponse($request, 'Role updated successfully.', 'admin.roles.index');
        } catch (\Exception $e) {
            return $this->handleErrorResponse($request, $e->getMessage(), 'admin.roles.edit', ['role' => $role]);
        }
    }

    /**
     * Remove the specified resource from storage (soft delete).
     *
     * @param Request $request
     * @param Role $role
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, Role $role)
    {
        try {
            $this->roleService->deleteRole($role->id);
            return $this->handleResponse($request, 'Role deleted successfully.', 'admin.roles.index');
        } catch (\Exception $e) {
            return $this->handleErrorResponse($request, $e->getMessage(), 'admin.roles.index');
        }
    }

    /**
     * Restore a soft-deleted role.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restore(Request $request, int $id)
    {
        try {
            $this->roleService->restoreRole($id);
            return $this->handleResponse($request, 'Role restored successfully.', 'admin.roles.index');
        } catch (\Exception $e) {
            return $this->handleErrorResponse($request, $e->getMessage(), 'admin.roles.index');
        }
    }

    /**
     * Permanently delete a role.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function forceDelete(Request $request, int $id)
    {
        try {
            $this->roleService->forceDeleteRole($id);
            return $this->handleResponse($request, 'Role permanently deleted.', 'admin.roles.index');
        } catch (\Exception $e) {
            return $this->handleErrorResponse($request, $e->getMessage(), 'admin.roles.index');
        }
    }
}