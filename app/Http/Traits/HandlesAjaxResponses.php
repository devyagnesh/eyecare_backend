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
        // Check for AJAX requests (multiple methods for compatibility)
        $isAjax = $request->expectsJson() 
            || $request->ajax() 
            || $request->wantsJson()
            || $request->header('X-Requested-With') === 'XMLHttpRequest'
            || $request->hasHeader('X-Requested-With');
        
        if ($isAjax) {
            return response()->json(array_merge([
                'success' => true,
                'message' => $message,
                'redirect' => $route ? route($route) : null,
                'forceRedirect' => false // Don't force redirect by default
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
     * @param array $errors
     * @return JsonResponse|RedirectResponse
     */
    protected function handleErrorResponse($request, string $message, ?string $route = null, int $statusCode = 400, array $errors = []): JsonResponse|RedirectResponse
    {
        // Check for AJAX requests (multiple methods for compatibility)
        $isAjax = $request->expectsJson() 
            || $request->ajax() 
            || $request->wantsJson()
            || $request->header('X-Requested-With') === 'XMLHttpRequest'
            || $request->hasHeader('X-Requested-With');
        
        if ($isAjax) {
            $response = [
                'success' => false,
                'message' => $message
            ];
            
            if (!empty($errors)) {
                $response['errors'] = $errors;
            }
            
            return response()->json($response, $statusCode);
        }

        $redirect = $route ? redirect()->route($route) : redirect()->back();
        return $redirect->with('error', $message);
    }
}

