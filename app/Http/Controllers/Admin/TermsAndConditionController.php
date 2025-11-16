<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreTermsAndConditionRequest;
use App\Http\Requests\Admin\UpdateTermsAndConditionRequest;
use App\Http\Traits\HandlesAjaxResponses;
use App\Models\TermsAndCondition;
use App\Services\TermsAndConditionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Terms and Condition Controller
 * 
 * Handles admin panel requests for terms and conditions management.
 * 
 * @package App\Http\Controllers\Admin
 */
class TermsAndConditionController extends Controller
{
    use HandlesAjaxResponses;

    /**
     * Create a new controller instance.
     *
     * @param TermsAndConditionService $termsService
     */
    public function __construct(
        private TermsAndConditionService $termsService
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

        $terms = $this->termsService->getTerms($filters, false);

        // If AJAX request, return only table HTML
        if ($request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json([
                'html' => view('admin.terms.index', compact('terms', 'filters'))->render()
            ]);
        }

        return view('admin.terms.index', compact('terms', 'filters'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.terms.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreTermsAndConditionRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreTermsAndConditionRequest $request)
    {
        try {
            $data = $request->validated();
            $data['created_by'] = Auth::id();
            $data['updated_by'] = Auth::id();
            
            $this->termsService->createTerms($data);
            return $this->handleResponse($request, 'Terms and conditions created successfully.', 'admin.terms.index');
        } catch (\Exception $e) {
            return $this->handleErrorResponse($request, $e->getMessage(), 'admin.terms.create');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param TermsAndCondition $termsAndCondition
     * @return \Illuminate\View\View
     */
    public function show(TermsAndCondition $termsAndCondition)
    {
        $stats = $this->termsService->getAcceptanceStats($termsAndCondition->id);
        $termsAndCondition->load(['creator', 'updater', 'acceptances.user']);
        
        return view('admin.terms.show', compact('termsAndCondition', 'stats'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param TermsAndCondition $termsAndCondition
     * @return \Illuminate\View\View
     */
    public function edit(TermsAndCondition $termsAndCondition)
    {
        return view('admin.terms.edit', compact('termsAndCondition'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateTermsAndConditionRequest $request
     * @param TermsAndCondition $termsAndCondition
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateTermsAndConditionRequest $request, TermsAndCondition $termsAndCondition)
    {
        try {
            $data = $request->validated();
            $data['updated_by'] = Auth::id();
            
            $this->termsService->updateTerms($termsAndCondition->id, $data);
            return $this->handleResponse($request, 'Terms and conditions updated successfully.', 'admin.terms.index');
        } catch (\Exception $e) {
            return $this->handleErrorResponse($request, $e->getMessage(), 'admin.terms.edit', ['termsAndCondition' => $termsAndCondition]);
        }
    }

    /**
     * Remove the specified resource from storage (soft delete).
     *
     * @param Request $request
     * @param TermsAndCondition $termsAndCondition
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, TermsAndCondition $termsAndCondition)
    {
        try {
            $this->termsService->deleteTerms($termsAndCondition->id);
            return $this->handleResponse($request, 'Terms and conditions deleted successfully.', 'admin.terms.index');
        } catch (\Exception $e) {
            return $this->handleErrorResponse($request, $e->getMessage(), 'admin.terms.index');
        }
    }

    /**
     * Restore a soft-deleted terms.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restore(Request $request, int $id)
    {
        try {
            $this->termsService->restoreTerms($id);
            return $this->handleResponse($request, 'Terms and conditions restored successfully.', 'admin.terms.index');
        } catch (\Exception $e) {
            return $this->handleErrorResponse($request, $e->getMessage(), 'admin.terms.index');
        }
    }

    /**
     * Permanently delete terms.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function forceDelete(Request $request, int $id)
    {
        try {
            $this->termsService->forceDeleteTerms($id);
            return $this->handleResponse($request, 'Terms and conditions permanently deleted.', 'admin.terms.index');
        } catch (\Exception $e) {
            return $this->handleErrorResponse($request, $e->getMessage(), 'admin.terms.index');
        }
    }
}

