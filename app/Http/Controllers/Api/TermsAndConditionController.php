<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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

}

