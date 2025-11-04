<?php

namespace App\Http\Traits;

use Illuminate\Support\Str;

trait HandlesSlugGeneration
{
    /**
     * Generate slug from name if not provided.
     *
     * @param array $validated
     * @return array
     */
    protected function generateSlug(array $validated): array
    {
        if (empty($validated['slug']) && !empty($validated['name'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        return $validated;
    }

    /**
     * Set is_active flag from request.
     *
     * @param array $validated
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    protected function setActiveFlag(array $validated, $request): array
    {
        $validated['is_active'] = $request->has('is_active');

        return $validated;
    }
}

