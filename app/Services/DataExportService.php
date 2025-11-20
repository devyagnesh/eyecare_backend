<?php

namespace App\Services;

use App\Models\User;
use App\Models\Store;
use Illuminate\Support\Facades\Log;

/**
 * Data Export Service
 * 
 * Handles business logic for exporting all user data.
 * 
 * @package App\Services
 */
class DataExportService
{
    /**
     * Export all data for a user.
     * 
     * Exports all data related to the user including:
     * - User profile information
     * - Store information
     * - Customers
     * - Eye Examinations
     * - Orders
     * - User Devices
     * - Notifications
     *
     * @param User $user
     * @return array
     * @throws \Exception
     */
    public function exportAllData(User $user): array
    {
        try {
            $store = $user->store;

            $data = [
                'export_info' => [
                    'exported_at' => now()->toIso8601String(),
                    'user_id' => $user->id,
                    'user_email' => $user->email,
                    'version' => '1.0',
                ],
                'user' => $this->formatUser($user),
                'store' => $store ? $this->formatStore($store) : null,
            ];

            // If user has a store, export store-related data
            if ($store) {
                $data['customers'] = $this->formatCustomers($store);
                $data['eye_examinations'] = $this->formatEyeExaminations($store);
                $data['orders'] = $this->formatOrders($store);
            } else {
                $data['customers'] = [];
                $data['eye_examinations'] = [];
                $data['orders'] = [];
            }

            // Export user devices
            $data['devices'] = $this->formatDevices($user);

            // Export notifications
            $data['notifications'] = $this->formatNotifications($user);

            Log::info('Data export completed', [
                'user_id' => $user->id,
                'store_id' => $store?->id,
            ]);

            return $data;
        } catch (\Exception $e) {
            Log::error('Failed to export data', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Format user data for export.
     *
     * @param User $user
     * @return array
     */
    private function formatUser(User $user): array
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'email_verified_at' => $user->email_verified_at?->toIso8601String(),
            'role' => $user->role ? [
                'id' => $user->role->id,
                'name' => $user->role->name,
                'slug' => $user->role->slug,
            ] : null,
            'is_blocked' => $user->is_blocked,
            'is_spam' => $user->is_spam,
            'created_at' => $user->created_at->toIso8601String(),
            'updated_at' => $user->updated_at->toIso8601String(),
            'deletion_requested_at' => $user->deletion_requested_at?->toIso8601String(),
            'scheduled_deletion_at' => $user->scheduled_deletion_at?->toIso8601String(),
        ];
    }

    /**
     * Format store data for export.
     *
     * @param Store $store
     * @return array
     */
    private function formatStore(Store $store): array
    {
        return [
            'id' => $store->id,
            'name' => $store->name,
            'email' => $store->email,
            'phone_number' => $store->phone_number,
            'address' => $store->address,
            'logo' => $store->logo,
            'is_active' => $store->is_active,
            'created_at' => $store->created_at->toIso8601String(),
            'updated_at' => $store->updated_at->toIso8601String(),
        ];
    }

    /**
     * Format customers data for export.
     *
     * @param Store $store
     * @return array
     */
    private function formatCustomers(Store $store): array
    {
        return $store->customers()->orderBy('created_at', 'desc')->get()->map(function ($customer) {
            return [
                'id' => $customer->id,
                'name' => $customer->name,
                'email' => $customer->email,
                'phone_number' => $customer->phone_number,
                'address' => $customer->address,
                'created_at' => $customer->created_at->toIso8601String(),
                'updated_at' => $customer->updated_at->toIso8601String(),
            ];
        })->toArray();
    }

    /**
     * Format eye examinations data for export.
     *
     * @param Store $store
     * @return array
     */
    private function formatEyeExaminations(Store $store): array
    {
        return $store->eyeExaminations()
            ->with('customer')
            ->orderBy('exam_date', 'desc')
            ->get()
            ->map(function ($examination) {
                return [
                    'id' => $examination->id,
                    'customer_id' => $examination->customer_id,
                    'customer_name' => $examination->customer?->name,
                    'exam_date' => $examination->exam_date->format('Y-m-d'),
                    'chief_complaint' => $examination->chief_complaint,
                    'old_rx_date' => $examination->old_rx_date?->format('Y-m-d'),
                    'od_va_unaided' => $examination->od_va_unaided,
                    'os_va_unaided' => $examination->os_va_unaided,
                    'od_sphere' => $examination->od_sphere,
                    'od_cylinder' => $examination->od_cylinder,
                    'od_axis' => $examination->od_axis,
                    'os_sphere' => $examination->os_sphere,
                    'os_cylinder' => $examination->os_cylinder,
                    'os_axis' => $examination->os_axis,
                    'add_power' => $examination->add_power,
                    'pd_distance' => $examination->pd_distance,
                    'pd_near' => $examination->pd_near,
                    'od_bcva' => $examination->od_bcva,
                    'os_bcva' => $examination->os_bcva,
                    'iop_od' => $examination->iop_od,
                    'iop_os' => $examination->iop_os,
                    'fundus_notes' => $examination->fundus_notes,
                    'diagnosis' => $examination->diagnosis,
                    'management_plan' => $examination->management_plan,
                    'next_recall_date' => $examination->next_recall_date?->format('Y-m-d'),
                    'created_at' => $examination->created_at->toIso8601String(),
                    'updated_at' => $examination->updated_at->toIso8601String(),
                ];
            })->toArray();
    }

    /**
     * Format orders data for export.
     *
     * @param Store $store
     * @return array
     */
    private function formatOrders(Store $store): array
    {
        return $store->orders()
            ->with(['customer', 'eyeExamination'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'customer_id' => $order->customer_id,
                    'customer_name' => $order->customer?->name,
                    'eye_examination_id' => $order->eye_examination_id,
                    'invoice_number' => $order->invoice_number,
                    'frame_photos' => $order->frame_photos,
                    'glass_details' => $order->glass_details,
                    'total_price' => $order->total_price,
                    'expected_completion_date' => $order->expected_completion_date?->format('Y-m-d'),
                    'status' => $order->status,
                    'notes' => $order->notes,
                    'created_at' => $order->created_at->toIso8601String(),
                    'updated_at' => $order->updated_at->toIso8601String(),
                    'deleted_at' => $order->deleted_at?->toIso8601String(),
                ];
            })->toArray();
    }

    /**
     * Format devices data for export.
     *
     * @param User $user
     * @return array
     */
    private function formatDevices(User $user): array
    {
        return $user->devices()->orderBy('created_at', 'desc')->get()->map(function ($device) {
            return [
                'id' => $device->id,
                'device_id' => $device->device_id,
                'device_type' => $device->device_type,
                'device_name' => $device->device_name,
                'os_name' => $device->os_name,
                'os_version' => $device->os_version,
                'browser_name' => $device->browser_name,
                'browser_version' => $device->browser_version,
                'ip_address' => $device->ip_address,
                'is_active' => $device->is_active,
                'last_active_at' => $device->last_active_at?->toIso8601String(),
                'created_at' => $device->created_at->toIso8601String(),
                'updated_at' => $device->updated_at->toIso8601String(),
            ];
        })->toArray();
    }

    /**
     * Format notifications data for export.
     *
     * @param User $user
     * @return array
     */
    private function formatNotifications(User $user): array
    {
        // Get all notifications relevant to the user
        $notifications = \App\Models\Notification::where(function ($q) use ($user) {
            $q->where('type', 'all')
                ->orWhere(function ($subQ) use ($user) {
                    $subQ->where('type', 'user')
                        ->where('user_id', $user->id);
                });
            
            if ($user->store) {
                $q->orWhere(function ($subQ) use ($user) {
                    $subQ->where('type', 'store')
                        ->where('store_id', $user->store->id);
                });
            }
        })
        ->orderBy('created_at', 'desc')
        ->get();

        return $notifications->map(function ($notification) {
            return [
                'id' => $notification->id,
                'title' => $notification->title,
                'body' => $notification->body,
                'data' => $notification->data ?? [],
                'type' => $notification->type,
                'status' => $notification->status,
                'created_at' => $notification->created_at->toIso8601String(),
                'updated_at' => $notification->updated_at->toIso8601String(),
            ];
        })->toArray();
    }
}

