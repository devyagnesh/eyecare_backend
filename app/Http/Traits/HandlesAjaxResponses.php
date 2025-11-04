<?php

namespace App\Http\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

trait HandlesAjaxResponses
{
    /**
     * Handle response for both AJAX and regular requests.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $message
     * @param string|null $route
     * @param array $additionalData
     * @return JsonResponse|RedirectResponse
     */
    protected function handleResponse($request, string $message, ?string $route = null, array $additionalData = []): JsonResponse|RedirectResponse
    {
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(array_merge([
                'success' => true,
                'message' => $message,
                'redirect' => $route ? route($route) : null
            ], $additionalData));
        }

        $redirect = $route ? redirect()->route($route) : redirect()->back();
        return $redirect->with('success', $message);
    }

    /**
     * Handle error response for both AJAX and regular requests.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $message
     * @param string|null $route
     * @param int $statusCode
     * @return JsonResponse|RedirectResponse
     */
    protected function handleErrorResponse($request, string $message, ?string $route = null, int $statusCode = 400): JsonResponse|RedirectResponse
    {
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => $message
            ], $statusCode);
        }

        $redirect = $route ? redirect()->route($route) : redirect()->back();
        return $redirect->with('error', $message);
    }
}

