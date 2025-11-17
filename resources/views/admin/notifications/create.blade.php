@extends('layouts.dashboard')

@section('title', 'Send Push Notification')

@section('subtitle', 'Send a push notification to app users')

@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="card custom-card">
            <div class="card-header">
                <div class="card-title">Send Push Notification</div>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.notifications.store') }}" method="POST" data-ajax="true" data-table-id="#notifications-table">
                    @csrf
                    <div class="row gy-4">
                        <div class="col-xl-12">
                            <label for="type" class="form-label">Notification Type <span class="text-danger">*</span></label>
                            <select class="form-control @error('type') is-invalid @enderror" id="type" name="type" required>
                                <option value="">Select notification type</option>
                                <option value="all" {{ old('type') === 'all' ? 'selected' : '' }}>All Users</option>
                                <option value="user" {{ old('type') === 'user' ? 'selected' : '' }}>Specific User</option>
                                <option value="store" {{ old('type') === 'store' ? 'selected' : '' }}>Store Users</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Select who should receive this notification</small>
                        </div>

                        <div class="col-xl-12" id="user-select-container" style="display: none;">
                            <label for="user_id" class="form-label">Select User <span class="text-danger">*</span></label>
                            <select class="form-control @error('user_id') is-invalid @enderror" id="user_id" name="user_id">
                                <option value="">Select a user</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-xl-12" id="store-select-container" style="display: none;">
                            <label for="store_id" class="form-label">Select Store <span class="text-danger">*</span></label>
                            <select class="form-control @error('store_id') is-invalid @enderror" id="store_id" name="store_id">
                                <option value="">Select a store</option>
                                @foreach($stores as $store)
                                    <option value="{{ $store->id }}" {{ old('store_id') == $store->id ? 'selected' : '' }}>
                                        {{ $store->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('store_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-xl-12">
                            <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" placeholder="Enter notification title" maxlength="255" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Maximum 255 characters</small>
                        </div>

                        <div class="col-xl-12">
                            <label for="body" class="form-label">Message Body <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('body') is-invalid @enderror" id="body" name="body" rows="5" placeholder="Enter notification message" maxlength="1000" required>{{ old('body') }}</textarea>
                            @error('body')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Maximum 1000 characters</small>
                        </div>

                        <div class="col-xl-12">
                            <label for="data" class="form-label">Additional Data (JSON - Optional)</label>
                            <textarea class="form-control @error('data') is-invalid @enderror" id="data" name="data" rows="3" placeholder='{"key": "value"}'>{{ old('data') }}</textarea>
                            @error('data')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Optional JSON data to send with the notification (e.g., {"screen": "orders", "order_id": 123})</small>
                        </div>
                    </div>
                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="ri-send-plane-line"></i> Send Notification
                        </button>
                        <a href="{{ route('admin.notifications.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        const typeSelect = $('#type');
        const userContainer = $('#user-select-container');
        const storeContainer = $('#store-select-container');
        const userIdSelect = $('#user_id');
        const storeIdSelect = $('#store_id');

        function toggleSelects() {
            const type = typeSelect.val();
            
            if (type === 'user') {
                userContainer.show();
                storeContainer.hide();
                userIdSelect.prop('required', true);
                storeIdSelect.prop('required', false);
                storeIdSelect.val('');
            } else if (type === 'store') {
                userContainer.hide();
                storeContainer.show();
                userIdSelect.prop('required', false);
                storeIdSelect.prop('required', true);
                userIdSelect.val('');
            } else {
                userContainer.hide();
                storeContainer.hide();
                userIdSelect.prop('required', false);
                storeIdSelect.prop('required', false);
                userIdSelect.val('');
                storeIdSelect.val('');
            }
        }

        // Initial toggle
        toggleSelects();

        // Toggle on change
        typeSelect.on('change', toggleSelects);

        // Validate JSON data field
        $('#data').on('blur', function() {
            const value = $(this).val().trim();
            if (value) {
                try {
                    JSON.parse(value);
                    $(this).removeClass('is-invalid');
                } catch (e) {
                    $(this).addClass('is-invalid');
                    if (!$(this).next('.invalid-feedback').length) {
                        $(this).after('<div class="invalid-feedback">Invalid JSON format</div>');
                    }
                }
            } else {
                $(this).removeClass('is-invalid');
            }
        });
    });
</script>
@endpush

