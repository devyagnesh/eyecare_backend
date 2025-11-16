@extends('layouts.dashboard')

@section('title', 'Terms and Conditions Details')

@section('page-header')
<div class="my-4 page-header-breadcrumb d-flex align-items-center justify-content-between flex-wrap gap-2">
    <div>
        <h1 class="page-title fw-medium fs-18 mb-2">Terms and Conditions Details</h1>
        <div>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.terms.index') }}">Terms and Conditions</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Details</li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="btn-list">
        <a href="{{ route('admin.terms.edit', $termsAndCondition) }}" class="btn btn-primary-light btn-wave">
            <i class="ri-pencil-line align-middle"></i> Edit
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-xl-8">
        <div class="card custom-card">
            <div class="card-header">
                <div class="card-title">Terms and Conditions Content</div>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <h4 class="fw-semibold">{{ $termsAndCondition->title }}</h4>
                    <span class="badge bg-info-transparent">Version {{ $termsAndCondition->version }}</span>
                    @if($termsAndCondition->is_active)
                        <span class="badge bg-success-transparent ms-2">Active</span>
                    @else
                        <span class="badge bg-secondary-transparent ms-2">Inactive</span>
                    @endif
                </div>
                <div class="terms-content border rounded p-4" style="max-height: 600px; overflow-y: auto;">
                    {!! nl2br(e($termsAndCondition->content)) !!}
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4">
        <div class="card custom-card">
            <div class="card-header">
                <div class="card-title">Information</div>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>ID:</strong>
                    <div>{{ $termsAndCondition->id }}</div>
                </div>
                <div class="mb-3">
                    <strong>Version:</strong>
                    <div><span class="badge bg-info-transparent">{{ $termsAndCondition->version }}</span></div>
                </div>
                <div class="mb-3">
                    <strong>Status:</strong>
                    <div>
                        @if($termsAndCondition->is_active)
                            <span class="badge bg-success-transparent">Active</span>
                        @else
                            <span class="badge bg-secondary-transparent">Inactive</span>
                        @endif
                    </div>
                </div>
                <div class="mb-3">
                    <strong>Created By:</strong>
                    <div>{{ $termsAndCondition->creator->name ?? 'N/A' }}</div>
                </div>
                <div class="mb-3">
                    <strong>Updated By:</strong>
                    <div>{{ $termsAndCondition->updater->name ?? 'N/A' }}</div>
                </div>
                <div class="mb-3">
                    <strong>Created At:</strong>
                    <div>{{ $termsAndCondition->created_at->format('F d, Y \a\t H:i A') }}</div>
                </div>
                <div class="mb-3">
                    <strong>Updated At:</strong>
                    <div>{{ $termsAndCondition->updated_at->format('F d, Y \a\t H:i A') }}</div>
                </div>
            </div>
        </div>

        <div class="card custom-card mt-3">
            <div class="card-header">
                <div class="card-title">Acceptance Statistics</div>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>Total Users:</strong>
                    <div class="h4 mb-0">{{ number_format($stats['total_users']) }}</div>
                </div>
                <div class="mb-3">
                    <strong>Accepted By:</strong>
                    <div class="h4 mb-0">{{ number_format($stats['accepted_count']) }}</div>
                </div>
                <div class="mb-3">
                    <strong>Acceptance Rate:</strong>
                    <div class="h4 mb-0">{{ $stats['acceptance_rate'] }}%</div>
                </div>
            </div>
        </div>

        <div class="card custom-card mt-3">
            <div class="card-header">
                <div class="card-title">Recent Acceptances</div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Accepted At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($termsAndCondition->acceptances()->with('user')->latest('accepted_at')->limit(10)->get() as $acceptance)
                            <tr>
                                <td>
                                    <div class="fw-medium">{{ $acceptance->user->name ?? 'N/A' }}</div>
                                    <small class="text-muted">{{ $acceptance->user->email ?? '' }}</small>
                                </td>
                                <td>
                                    <small>{{ $acceptance->accepted_at->format('M d, Y') }}</small>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2" class="text-center text-muted">No acceptances yet</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="d-flex gap-2 mt-3">
            <a href="{{ route('admin.terms.edit', $termsAndCondition) }}" class="btn btn-primary flex-fill">
                <i class="ri-pencil-line"></i> Edit
            </a>
            <form action="{{ route('admin.terms.destroy', $termsAndCondition) }}" method="POST" class="flex-fill" onsubmit="return confirm('Are you sure?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger w-100">
                    <i class="ri-delete-bin-line"></i> Delete
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

