<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

/**
 * Geolocation Service
 * 
 * Handles IP geolocation using free APIs.
 * 
 * @package App\Services
 */
class GeolocationService
{
    /**
     * Get location data from IP address.
     *
     * @param string $ipAddress
     * @return array|null
     */
    public function getLocationFromIp(string $ipAddress): ?array
    {
        // Skip private/local IPs
        if ($this->isPrivateIp($ipAddress)) {
            return null;
        }

        // Check cache first (cache for 30 days)
        $cacheKey = "geolocation_{$ipAddress}";
        $cached = Cache::get($cacheKey);
        if ($cached !== null) {
            return $cached;
        }

        try {
            // Try ipapi.co first (free, 1000 requests/day)
            $location = $this->getLocationFromIpApi($ipAddress);
            
            if (!$location) {
                // Fallback to ip-api.com (free, 45 requests/minute)
                $location = $this->getLocationFromIpApiCom($ipAddress);
            }

            if ($location) {
                // Cache for 30 days
                Cache::put($cacheKey, $location, now()->addDays(30));
                return $location;
            }
        } catch (\Exception $e) {
            Log::warning('Geolocation service error', [
                'ip' => $ipAddress,
                'error' => $e->getMessage(),
            ]);
        }

        return null;
    }

    /**
     * Get location from ipapi.co
     *
     * @param string $ipAddress
     * @return array|null
     */
    private function getLocationFromIpApi(string $ipAddress): ?array
    {
        try {
            $response = Http::timeout(5)->get("https://ipapi.co/{$ipAddress}/json/");
            
            if ($response->successful()) {
                $data = $response->json();
                
                if (isset($data['latitude']) && isset($data['longitude'])) {
                    return [
                        'latitude' => (float) $data['latitude'],
                        'longitude' => (float) $data['longitude'],
                        'city' => $data['city'] ?? null,
                        'region' => $data['region'] ?? null,
                        'country' => $data['country_name'] ?? null,
                        'country_code' => $data['country_code'] ?? null,
                    ];
                }
            }
        } catch (\Exception $e) {
            Log::debug('ipapi.co geolocation failed', ['ip' => $ipAddress, 'error' => $e->getMessage()]);
        }

        return null;
    }

    /**
     * Get location from ip-api.com
     *
     * @param string $ipAddress
     * @return array|null
     */
    private function getLocationFromIpApiCom(string $ipAddress): ?array
    {
        try {
            $response = Http::timeout(5)->get("http://ip-api.com/json/{$ipAddress}", [
                'fields' => 'status,message,country,countryCode,region,regionName,city,lat,lon',
            ]);
            
            if ($response->successful()) {
                $data = $response->json();
                
                if (isset($data['status']) && $data['status'] === 'success') {
                    return [
                        'latitude' => (float) $data['lat'],
                        'longitude' => (float) $data['lon'],
                        'city' => $data['city'] ?? null,
                        'region' => $data['regionName'] ?? null,
                        'country' => $data['country'] ?? null,
                        'country_code' => $data['countryCode'] ?? null,
                    ];
                }
            }
        } catch (\Exception $e) {
            Log::debug('ip-api.com geolocation failed', ['ip' => $ipAddress, 'error' => $e->getMessage()]);
        }

        return null;
    }

    /**
     * Check if IP is private/local.
     *
     * @param string $ipAddress
     * @return bool
     */
    private function isPrivateIp(string $ipAddress): bool
    {
        return !filter_var($ipAddress, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE);
    }
}

