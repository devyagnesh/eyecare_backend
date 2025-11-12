<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Http\Traits\HandlesAjaxResponses;
use App\Models\User;
use App\Models\Role;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * User Controller
 * 
 * Handles admin panel requests for users management.
 * 
 * @package App\Http\Controllers\Admin
 */
class UserController extends Controller
{
    use HandlesAjaxResponses;

    /**
     * Create a new controller instance.
     *
     * @param UserService $userService
     */
    public function __construct(
        private UserService $userService
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
            'role_id' => $request->get('role_id'),
            'email_verified' => $request->get('email_verified'),
            'sort_by' => $request->get('sort_by', 'created_at'),
            'sort_order' => $request->get('sort_order', 'desc'),
        ];

        $users = $this->userService->getUsers($filters, false);
        $roles = Role::active()->orderBy('name')->get();

        // If AJAX request, return only table HTML
        if ($request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json([
                'html' => view('admin.users.index', compact('users', 'roles', 'filters'))->render()
            ]);
        }

        return view('admin.users.index', compact('users', 'roles', 'filters'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::active()->orderBy('name')->get();
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreUserRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreUserRequest $request)
    {
        try {
            $this->userService->createUser($request->validated());
            return $this->handleResponse($request, 'User created successfully.', 'admin.users.index');
        } catch (\Exception $e) {
            return $this->handleErrorResponse($request, $e->getMessage(), 'admin.users.create');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user->load(['role', 'role.permissions']);
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles = Role::active()->orderBy('name')->get();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateUserRequest $request
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        try {
            $this->userService->updateUser($user->id, $request->validated());
            return $this->handleResponse($request, 'User updated successfully.', 'admin.users.index');
        } catch (\Exception $e) {
            return $this->handleErrorResponse($request, $e->getMessage(), 'admin.users.edit', ['user' => $user]);
        }
    }

    /**
     * Remove the specified resource from storage (soft delete).
     *
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, User $user)
    {
        if ($user->id === auth()->id()) {
            return $this->handleErrorResponse($request, 'You cannot delete your own account.', 'admin.users.index', 403);
        }

        try {
            $this->userService->deleteUser($user->id);
            return $this->handleResponse($request, 'User deleted successfully.', 'admin.users.index');
        } catch (\Exception $e) {
            return $this->handleErrorResponse($request, $e->getMessage(), 'admin.users.index');
        }
    }

    /**
     * Restore a soft-deleted user.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restore(Request $request, int $id)
    {
        try {
            $this->userService->restoreUser($id);
            return $this->handleResponse($request, 'User restored successfully.', 'admin.users.index');
        } catch (\Exception $e) {
            return $this->handleErrorResponse($request, $e->getMessage(), 'admin.users.index');
        }
    }

    /**
     * Permanently delete a user.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function forceDelete(Request $request, int $id)
    {
        try {
            $this->userService->forceDeleteUser($id);
            return $this->handleResponse($request, 'User permanently deleted.', 'admin.users.index');
        } catch (\Exception $e) {
            return $this->handleErrorResponse($request, $e->getMessage(), 'admin.users.index');
        }
    }
}