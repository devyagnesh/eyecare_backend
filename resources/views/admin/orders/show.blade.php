@extends('layouts.dashboard')

@section('title', 'Order Details')

@section('subtitle', 'View order information')

@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="card custom-card">
            <div class="card-header justify-content-between">
                <div class="card-title">Order Details</div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary btn-wave">
                        <i class="ri-arrow-left-line me-2"></i>Back
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row gy-4">
                    <div class="col-xl-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Invoice Number</label>
                            <p class="fw-semibold mb-0">{{ $order->invoice_number }}</p>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Status</label>
                            <p class="mb-0">
                                @php
                                    $statusColors = [
                                        'pending' => 'warning',
                                        'processing' => 'info',
                                        'completed' => 'success',
                                        'cancelled' => 'danger'
                                    ];
                                    $color = $statusColors[$order->status] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $color }}-transparent">{{ ucfirst($order->status) }}</span>
                            </p>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Total Price</label>
                            <p class="fw-semibold mb-0">${{ number_format($order->total_price, 2) }}</p>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Expected Completion Date</label>
                            <p class="fw-semibold mb-0">{{ $order->expected_completion_date ? $order->expected_completion_date->format('M d, Y') : 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="col-xl-12">
                        <hr>
                        <h6 class="mb-3">Customer Information</h6>
                    </div>
                    @if($order->customer)
                    <div class="col-xl-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Customer Name</label>
                            <p class="fw-semibold mb-0">{{ $order->customer->name }}</p>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Email</label>
                            <p class="fw-semibold mb-0">{{ $order->customer->email }}</p>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Phone Number</label>
                            <p class="fw-semibold mb-0">{{ $order->customer->phone_number ?? 'N/A' }}</p>
                        </div>
                    </div>
                    @endif
                    <div class="col-xl-12">
                        <hr>
                        <h6 class="mb-3">Store Information</h6>
                    </div>
                    @if($order->store)
                    <div class="col-xl-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Store Name</label>
                            <p class="fw-semibold mb-0">{{ $order->store->name }}</p>
                        </div>
                    </div>
                    @if($order->store->user)
                    <div class="col-xl-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Store Owner</label>
                            <p class="fw-semibold mb-0">{{ $order->store->user->name }}</p>
                        </div>
                    </div>
                    @endif
                    @endif
                    @if($order->eyeExamination)
                    <div class="col-xl-12">
                        <hr>
                        <h6 class="mb-3">Eye Examination</h6>
                    </div>
                    <div class="col-xl-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Exam Date</label>
                            <p class="fw-semibold mb-0">{{ $order->eyeExamination->exam_date->format('M d, Y') }}</p>
                        </div>
                    </div>
                    @endif
                    @if($order->glass_details)
                    <div class="col-xl-12">
                        <hr>
                        <h6 class="mb-3">Glass Details</h6>
                    </div>
                    <div class="col-xl-12">
                        <div class="mb-3">
                            <p class="mb-0">{{ $order->glass_details }}</p>
                        </div>
                    </div>
                    @endif
                    @if($order->notes)
                    <div class="col-xl-12">
                        <hr>
                        <h6 class="mb-3">Notes</h6>
                    </div>
                    <div class="col-xl-12">
                        <div class="mb-3">
                            <p class="mb-0">{{ $order->notes }}</p>
                        </div>
                    </div>
                    @endif
                    @if($order->frame_photos && count($order->frame_photos) > 0)
                    <div class="col-xl-12">
                        <hr>
                        <h6 class="mb-3">Frame Photos</h6>
                    </div>
                    <div class="col-xl-12">
                        <div class="row g-3">
                            @foreach($order->frame_photos as $photo)
                            <div class="col-md-3">
                                <div class="card border">
                                    <img src="{{ asset('storage/' . $photo) }}" alt="Frame Photo" class="card-img-top" style="max-height: 200px; object-fit: cover;">
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    @if($order->invoice_pdf_path)
                    <div class="col-xl-12">
                        <hr>
                        <h6 class="mb-3">Invoice</h6>
                    </div>
                    <div class="col-xl-12">
                        <div class="mb-3">
                            <a href="{{ asset('storage/' . $order->invoice_pdf_path) }}" target="_blank" class="btn btn-primary btn-wave">
                                <i class="ri-file-pdf-line me-2"></i>Download Invoice PDF
                            </a>
                        </div>
                    </div>
                    @endif
                    <div class="col-xl-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Created At</label>
                            <p class="fw-semibold mb-0">{{ $order->created_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Updated At</label>
                            <p class="fw-semibold mb-0">{{ $order->updated_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

