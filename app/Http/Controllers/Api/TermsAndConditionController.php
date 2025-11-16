<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AcceptTermsRequest;
use App\Models\TermsAndCondition;
use App\Services\TermsAndConditionService;
use Illuminate\Http\Request;

/**
 * Terms and Condition API Controller
 * 
 * Handles API requests for terms and conditions.
 * 
 * @package App\Http\Controllers\Api
 */
class TermsAndConditionController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @param TermsAndConditionService $termsService
     */
    public function __construct(
        private TermsAndConditionService $termsService
    ) {}

    /**
     * Get the latest active terms and conditions.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLatest(Request $request)
    {
        $terms = $this->termsService->getLatestTerms();

        if (!$terms) {
            return response()->json([
                'success' => false,
                'message' => 'No active terms and conditions found.',
            ], 404);
        }

        $user = $request->user();
        $hasAccepted = false;
        
        if ($user) {
            $hasAccepted = $user->hasAcceptedLatestTerms();
        }

        return response()->json([
            'success' => true,
            'data' => [
                'terms' => [
                    'id' => $terms->id,
                    'title' => $terms->title,
                    'content' => $terms->content,
                    'version' => $terms->version,
                    'created_at' => $terms->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $terms->updated_at->format('Y-m-d H:i:s'),
                ],
                'has_accepted' => $hasAccepted,
            ],
        ], 200);
    }

    /**
     * Accept terms and conditions.
     *
     * @param AcceptTermsRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function accept(AcceptTermsRequest $request)
    {
        $user = $request->user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
            ], 401);
        }

        try {
            $ipAddress = $request->ip();
            $userAgent = $request->userAgent();

            $this->termsService->recordAcceptance(
                $user,
                $request->validated()['terms_and_condition_id'],
                $ipAddress,
                $userAgent
            );

            return response()->json([
                'success' => true,
                'message' => 'Terms and conditions accepted successfully.',
                'data' => [
                    'has_accepted' => true,
                ],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to accept terms and conditions.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Check if user has accepted latest terms.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkAcceptance(Request $request)
    {
        $user = $request->user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
            ], 401);
        }

        $latestTerms = $this->termsService->getLatestTerms();
        $hasAccepted = $user->hasAcceptedLatestTerms();
        $latestAccepted = $user->getLatestAcceptedTerms();

        return response()->json([
            'success' => true,
            'data' => [
                'has_accepted_latest' => $hasAccepted,
                'latest_terms' => $latestTerms ? [
                    'id' => $latestTerms->id,
                    'version' => $latestTerms->version,
                    'updated_at' => $latestTerms->updated_at->format('Y-m-d H:i:s'),
                ] : null,
                'last_accepted' => $latestAccepted ? [
                    'terms_id' => $latestAccepted->terms_and_condition_id,
                    'accepted_at' => $latestAccepted->accepted_at->format('Y-m-d H:i:s'),
                ] : null,
            ],
        ], 200);
    }
}

