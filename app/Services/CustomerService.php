<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Store;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CustomerService
{
    /**
     * Get all customers for a store with filters.
     *
     * @param Store $store
     * @param array $filters
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Collection
     */
    public function getCustomers(Store $store, array $filters = [])
    {
        $query = Customer::where('store_id', $store->id);

        // Search functionality (searches in name, email, and phone_number)
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone_number', 'like', "%{$search}%");
            });
        }

        // Filter by exact name match
        if (!empty($filters['name'])) {
            $query->where('name', $filters['name']);
        }

        // Filter by exact email match
        if (!empty($filters['email'])) {
            $query->where('email', $filters['email']);
        }

        // Filter by exact phone_number match
        if (!empty($filters['phone_number'])) {
            $query->where('phone_number', $filters['phone_number']);
        }

        // Filter by date range (created_at)
        if (!empty($filters['created_from'])) {
            $query->whereDate('created_at', '>=', $filters['created_from']);
        }
        if (!empty($filters['created_to'])) {
            $query->whereDate('created_at', '<=', $filters['created_to']);
        }

        // Sorting
        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortOrder = $filters['sort_order'] ?? 'desc';
        
        $allowedSortFields = ['name', 'email', 'phone_number', 'created_at', 'updated_at'];
        if (!in_array($sortBy, $allowedSortFields)) {
            $sortBy = 'created_at';
        }
        
        $sortOrder = strtolower($sortOrder);
        if (!in_array($sortOrder, ['asc', 'desc'])) {
            $sortOrder = 'desc';
        }
        
        $query->orderBy($sortBy, $sortOrder);

        // Pagination toggle
        $paginated = $filters['paginated'] ?? true;
        
        if ($paginated) {
            $perPage = min(max((int) ($filters['per_page'] ?? 15), 1), 100);
            return $query->paginate($perPage);
        }
        
        return $query->get();
    }

    /**
     * Create a new customer.
     *
     * @param Store $store
     * @param array $data
     * @return Customer
     * @throws \Exception
     */
    public function createCustomer(Store $store, array $data): Customer
    {
        // Ensure store_id matches authenticated user's store
        if (isset($data['store_id']) && (int)$data['store_id'] !== $store->id) {
            throw new \Exception('Store ID does not match your store.', 403);
        }

        try {
            DB::beginTransaction();

            $customer = Customer::create([
                'store_id' => (int)$store->id,
                'name' => $data['name'],
                'email' => $data['email'] ?? null,
                'phone_number' => $data['phone_number'],
                'address' => $data['address'] ?? null,
            ]);
            
            // Verify store_id was saved correctly
            if ($customer->store_id !== (int)$store->id) {
                Log::error('Customer store_id mismatch after creation', [
                    'customer_id' => $customer->id,
                    'saved_store_id' => $customer->store_id,
                    'expected_store_id' => $store->id,
                ]);
                throw new \Exception('Failed to save customer with correct store ID.', 500);
            }

            DB::commit();

            Log::info('Customer created successfully', [
                'customer_id' => $customer->id,
                'store_id' => $store->id,
            ]);

            return $customer->fresh();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create customer', [
                'error' => $e->getMessage(),
                'store_id' => $store->id,
                'data' => $data,
            ]);
            throw $e;
        }
    }

    /**
     * Update a customer.
     *
     * @param Customer $customer
     * @param Store $store
     * @param array $data
     * @return Customer
     * @throws \Exception
     */
    public function updateCustomer(Customer $customer, Store $store, array $data): Customer
    {
        // Refresh customer to ensure we have latest data
        $customer->refresh();
        
        // Ensure customer belongs to the store
        if ((int)$customer->store_id !== (int)$store->id) {
            Log::warning('Customer store mismatch in updateCustomer', [
                'customer_id' => $customer->id,
                'customer_store_id' => $customer->store_id,
                'store_id' => $store->id,
            ]);
            throw new \Exception('Customer does not belong to your store.', 403);
        }

        // Ensure store_id matches authenticated user's store if provided
        if (isset($data['store_id']) && (int)$data['store_id'] !== $store->id) {
            throw new \Exception('Store ID does not match your store.', 403);
        }

        try {
            DB::beginTransaction();

            $customer->update([
                'name' => $data['name'] ?? $customer->name,
                'email' => $data['email'] ?? $customer->email,
                'phone_number' => $data['phone_number'] ?? $customer->phone_number,
                'address' => $data['address'] ?? $customer->address,
            ]);

            DB::commit();

            Log::info('Customer updated successfully', [
                'customer_id' => $customer->id,
                'store_id' => $store->id,
            ]);

            return $customer->fresh();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update customer', [
                'error' => $e->getMessage(),
                'customer_id' => $customer->id,
                'data' => $data,
            ]);
            throw $e;
        }
    }

    /**
     * Delete a customer.
     *
     * @param Customer $customer
     * @param Store $store
     * @return bool
     * @throws \Exception
     */
    public function deleteCustomer(Customer $customer, Store $store): bool
    {
        // Ensure customer belongs to the store
        if ((int)$customer->store_id !== (int)$store->id) {
            throw new \Exception('Customer does not belong to your store.', 403);
        }

        try {
            $customerId = $customer->id;
            $storeId = $customer->store_id;

            $customer->delete();

            Log::info('Customer deleted successfully', [
                'customer_id' => $customerId,
                'store_id' => $storeId,
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to delete customer', [
                'error' => $e->getMessage(),
                'customer_id' => $customer->id,
            ]);
            throw $e;
        }
    }

    /**
     * Format customer data for API response.
     *
     * @param Customer $customer
     * @return array
     */
    public function formatCustomer(Customer $customer): array
    {
        return [
            'id' => $customer->id,
            'store_id' => $customer->store_id,
            'name' => $customer->name,
            'email' => $customer->email,
            'phone_number' => $customer->phone_number,
            'address' => $customer->address,
            'created_at' => $customer->created_at->toIso8601String(),
            'updated_at' => $customer->updated_at->toIso8601String(),
        ];
    }
}

