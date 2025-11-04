<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePermissionRequest;
use App\Http\Requests\Admin\UpdatePermissionRequest;
use App\Http\Traits\HandlesAjaxResponses;
use App\Http\Traits\HandlesSlugGeneration;
use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    use HandlesAjaxResponses, HandlesSlugGeneration;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $permissions = Permission::latest()->get();
        $modules = Permission::distinct()->pluck('module')->filter()->sort()->values();
        return view('admin.permissions.index', compact('permissions', 'modules'));
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
     */
    public function store(StorePermissionRequest $request)
    {
        $validated = $request->validated();
        $validated = $this->generateSlug($validated);
        $validated = $this->setActiveFlag($validated, $request);

        Permission::create($validated);

        return $this->handleResponse($request, 'Permission created successfully.', 'admin.permissions.index');
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
     */
    public function update(UpdatePermissionRequest $request, Permission $permission)
    {
        $validated = $request->validated();
        $validated = $this->generateSlug($validated);
        $validated = $this->setActiveFlag($validated, $request);

        $permission->update($validated);

        return $this->handleResponse($request, 'Permission updated successfully.', 'admin.permissions.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Permission $permission)
    {
        if ($permission->roles()->count() > 0) {
            return $this->handleErrorResponse($request, 'Cannot delete permission. It is assigned to one or more roles.', 'admin.permissions.index', 403);
        }

        $permission->delete();

        return $this->handleResponse($request, 'Permission deleted successfully.', 'admin.permissions.index');
    }
}