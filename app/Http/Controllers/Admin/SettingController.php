<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreSettingRequest;
use App\Http\Requests\Admin\UpdateSettingRequest;
use App\Http\Traits\HandlesAjaxResponses;
use App\Services\SettingService;
use App\Models\Setting;
use Illuminate\Http\Request;

/**
 * Setting Controller
 * 
 * Handles admin panel requests for settings management.
 * 
 * @package App\Http\Controllers\Admin
 */
class SettingController extends Controller
{
    use HandlesAjaxResponses;

    /**
     * Create a new controller instance.
     *
     * @param SettingService $settingService
     */
    public function __construct(
        private SettingService $settingService
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
            'group' => $request->get('group'),
            'type' => $request->get('type'),
            'sort_by' => $request->get('sort_by', 'key'),
            'sort_order' => $request->get('sort_order', 'asc'),
        ];

        $settings = $this->settingService->getSettings($filters, false);
        $groups = $this->settingService->getGroups();

        // If AJAX request, return only table HTML
        if ($request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json([
                'html' => view('admin.settings.index', compact('settings', 'groups', 'filters'))->render()
            ]);
        }

        return view('admin.settings.index', compact('settings', 'groups', 'filters'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $groups = $this->settingService->getGroups();
        return view('admin.settings.create', compact('groups'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreSettingRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreSettingRequest $request)
    {
        try {
            $this->settingService->createSetting($request->validated());
            return $this->handleResponse($request, 'Setting created successfully.', 'admin.settings.index');
        } catch (\Exception $e) {
            return $this->handleErrorResponse($request, $e->getMessage(), 'admin.settings.create');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Setting $setting
     * @return \Illuminate\View\View
     */
    public function show(Setting $setting)
    {
        return view('admin.settings.show', compact('setting'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Setting $setting
     * @return \Illuminate\View\View
     */
    public function edit(Setting $setting)
    {
        $groups = $this->settingService->getGroups();
        return view('admin.settings.edit', compact('setting', 'groups'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateSettingRequest $request
     * @param Setting $setting
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateSettingRequest $request, Setting $setting)
    {
        try {
            $this->settingService->updateSetting($setting->id, $request->validated());
            return $this->handleResponse($request, 'Setting updated successfully.', 'admin.settings.index');
        } catch (\Exception $e) {
            return $this->handleErrorResponse($request, $e->getMessage(), 'admin.settings.edit', ['setting' => $setting]);
        }
    }

    /**
     * Remove the specified resource from storage (soft delete).
     *
     * @param Request $request
     * @param Setting $setting
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, Setting $setting)
    {
        try {
            $this->settingService->deleteSetting($setting->id);
            return $this->handleResponse($request, 'Setting deleted successfully.', 'admin.settings.index');
        } catch (\Exception $e) {
            return $this->handleErrorResponse($request, $e->getMessage(), 'admin.settings.index');
        }
    }

    /**
     * Restore a soft-deleted setting.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restore(Request $request, int $id)
    {
        try {
            $this->settingService->restoreSetting($id);
            return $this->handleResponse($request, 'Setting restored successfully.', 'admin.settings.index');
        } catch (\Exception $e) {
            return $this->handleErrorResponse($request, $e->getMessage(), 'admin.settings.index');
        }
    }

    /**
     * Permanently delete a setting.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function forceDelete(Request $request, int $id)
    {
        try {
            $this->settingService->forceDeleteSetting($id);
            return $this->handleResponse($request, 'Setting permanently deleted.', 'admin.settings.index');
        } catch (\Exception $e) {
            return $this->handleErrorResponse($request, $e->getMessage(), 'admin.settings.index');
        }
    }
}

