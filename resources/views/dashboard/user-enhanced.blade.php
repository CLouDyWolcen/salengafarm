@extends('layouts.public')

@push('styles')
<link href="{{ asset('css/loading.css') }}?v={{ time() }}" rel="stylesheet">
<style>
    .btn-sm {
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
    }
    .btn-sm i {
        font-size: 0.875rem;
    }
    .request-row {
        cursor: pointer;
        transition: background-color 0.2s ease;
    }
    .request-row:hover {
        background-color: #f8f9fa;
    }
    .request-details {
        background-color: #f8f9fa;
        border-left: 3px solid #198754;
    }
    .plant-list-item {
        padding: 8px 12px;
        background: white;
        border-radius: 6px;
        margin-bottom: 8px;
        border: 1px solid #e0e0e0;
    }
    .plant-list-item:last-child {
        margin-bottom: 0;
    }
    .badge-count {
        background: #198754;
        color: white;
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    
    /* Fixed height for recent requests table - max 8 rows */
    .recent-requests-wrapper {
        max-height: 480px; /* Approximately 8 rows at ~60px per row */
        overflow-y: auto;
        overflow-x: hidden;
    }
    
    /* Sticky table header */
    .recent-requests-wrapper thead {
        position: sticky;
        top: 0;
        z-index: 10;
        background-color: #f8f9fa;
    }
    
    /* Custom scrollbar styling */
    .recent-requests-wrapper::-webkit-scrollbar {
        width: 8px;
    }
    
    .recent-requests-wrapper::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }
    
    .recent-requests-wrapper::-webkit-scrollbar-thumb {
        background: #198754;
        border-radius: 4px;
    }
    
    .recent-requests-wrapper::-webkit-scrollbar-thumb:hover {
        background: #146c43;
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-0"><i class="fas fa-gauge me-2 text-success"></i>Dashboard</h4>
    </div>

    <div class="row g-3">
        <div class="col-12 col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex align-items-center justify-content-between">
                    <h6 class="mb-0">Recent Requests</h6>
                    <span class="badge bg-success">{{ $requests->count() }} Total</span>
                </div>
                <div class="card-body p-0">
                    <div class="recent-requests-wrapper">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 60px;">ID</th>
                                    <th>Request Date</th>
                                    <th>Plants</th>
                                    <th>Status</th>
                                    <th style="width: 150px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($requests as $r)
                                <tr class="request-row" data-request-id="{{ $r->id }}">
                                    <td>#{{ $r->id }}</td>
                                    <td>{{ optional($r->created_at)->format('M d, Y') ?? '—' }}</td>
                                    <td>
                                        <span class="badge-count">{{ is_array($r->items_json) ? count($r->items_json) : 0 }}</span>
                                        <span class="text-muted small">plants</span>
                                    </td>
                                    <td>
                                        @php
                                            $status = strtolower($r->status ?? 'pending');
                                            $displayStatus = $status === 'sent' ? 'received' : $status;
                                            $badgeClass = in_array($status, ['approved','sent','completed']) ? 'success' : ($status === 'pending' ? 'warning' : 'secondary');
                                        @endphp
                                        <span class="badge bg-{{ $badgeClass }}">
                                            {{ ucfirst($displayStatus) }}
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-success toggle-details" data-request-id="{{ $r->id }}">
                                            <i class="fas fa-eye"></i> View
                                        </button>
                                        @if($r->pdf_path && \Storage::exists($r->pdf_path))
                                        <a href="{{ route('requests.download-pdf', $r->id) }}" class="btn btn-sm btn-outline-primary" title="Download PDF">
                                            <i class="fas fa-download"></i>
                                        </a>
                                        @endif
                                    </td>
                                </tr>
                                <tr class="request-details-row d-none" id="details-{{ $r->id }}">
                                    <td colspan="5">
                                        <div class="request-details p-3">
                                            <h6 class="mb-3"><i class="fas fa-list me-2"></i>Plant Details</h6>
                                            @if(is_array($r->items_json) && count($r->items_json) > 0)
                                                <div class="row">
                                                    @foreach($r->items_json as $item)
                                                    <div class="col-md-6 mb-2">
                                                        <div class="plant-list-item">
                                                            <div class="d-flex justify-content-between align-items-start">
                                                                <div>
                                                                    <strong>{{ $item['name'] ?? 'Unknown Plant' }}</strong>
                                                                    @if(!empty($item['code']))
                                                                        <span class="text-muted small">({{ $item['code'] }})</span>
                                                                    @endif
                                                                    @if(!empty($item['quantity']))
                                                                        <div class="text-muted small mt-1">
                                                                            Quantity: <strong>{{ $item['quantity'] }}</strong>
                                                                        </div>
                                                                    @endif
                                                                    @if(!empty($item['height']) || !empty($item['spread']) || !empty($item['spacing']))
                                                                        <div class="text-muted small mt-1">
                                                                            @if(!empty($item['height']))
                                                                                H: {{ $item['height'] }}mm
                                                                            @endif
                                                                            @if(!empty($item['spread']))
                                                                                | S: {{ $item['spread'] }}mm
                                                                            @endif
                                                                            @if(!empty($item['spacing']))
                                                                                | Sp: {{ $item['spacing'] }}mm
                                                                            @endif
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <p class="text-muted mb-0">No plant details available.</p>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        <i class="fas fa-seedling fa-2x mb-2 d-block"></i>
                                        No requests yet. Go to <a href="{{ route('public.plants') }}">Home</a> to place a request.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-4">
            <div class="mb-3">
                <a href="{{ route('public.plants') }}" class="btn btn-success w-100">
                    <i class="fas fa-rotate-left me-2"></i>Return to Home to Request
                </a>
            </div>
            @if(!auth()->user()->is_client)
            <div class="mb-3">
                <button type="button" class="btn btn-success w-100" data-bs-toggle="modal" data-bs-target="#clientRequestModal">
                    <i class="fas fa-user-plus me-2"></i>Request to be a Client
                </button>
            </div>
            @endif
            <div class="card shadow-sm mb-3">
                <div class="card-body">
                    <h6 class="mb-2"><i class="fas fa-lightbulb me-2 text-warning"></i>Quick Tips</h6>
                    <ul class="mb-0 small">
                        <li>Click "View" to see plant details in your requests</li>
                        <li>Download PDF quotations when available</li>
                        <li>We'll email you when your quotation is ready</li>
                        <li>Track your request status in real-time</li>
                    </ul>
                </div>
            </div>
            @if($requests->count() > 0)
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="mb-2"><i class="fas fa-chart-simple me-2 text-info"></i>Request Summary</h6>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="small">Pending:</span>
                        <strong>{{ $requests->where('status', 'pending')->count() }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="small">Received:</span>
                        <strong>{{ $requests->where('status', 'sent')->count() }}</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="small">Total Plants:</span>
                        <strong>{{ $requests->sum(function($r) { return is_array($r->items_json) ? count($r->items_json) : 0; }) }}</strong>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Client Request Modal (keeping existing modal code) -->
@include('dashboard.partials.client-request-modal')

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle request details
    document.querySelectorAll('.toggle-details').forEach(button => {
        button.addEventListener('click', function(e) {
            e.stopPropagation();
            const requestId = this.getAttribute('data-request-id');
            const detailsRow = document.getElementById(`details-${requestId}`);
            const icon = this.querySelector('i');
            
            if (detailsRow.classList.contains('d-none')) {
                detailsRow.classList.remove('d-none');
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
                this.innerHTML = '<i class="fas fa-eye-slash"></i> Hide';
            } else {
                detailsRow.classList.add('d-none');
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
                this.innerHTML = '<i class="fas fa-eye"></i> View';
            }
        });
    });
});
</script>
@endsection
