@extends('layouts.public')

@section('title', 'Inquiry Response - Salenga Farm')

@section('content')
<div class="response-container">
    <div class="response-header">
        <h4 class="response-title">
            <i class="fas fa-clipboard-check"></i>
            @if($request->request_type === 'client')
                RFQ Response #{{ $request->id }}
            @else
                Inquiry Response #{{ $request->id }}
            @endif
        </h4>
        <div class="header-actions">
            @if($request->request_type === 'client' && $request->pdf_path)
                <a href="{{ route('requests.download-pdf', $request->id) }}" class="download-btn">
                    <i class="fas fa-download"></i>Download RFQ
                </a>
            @endif
            <a href="{{ route('my-requests.index') }}" class="back-btn">
                <i class="fas fa-arrow-left"></i>Back to My Requests
            </a>
        </div>
    </div>

    <!-- Inquiry Information -->
    <div class="info-card">
        <div class="info-card-header">
            <h6>Inquiry Information</h6>
        </div>
        <div class="info-card-body">
            <div class="info-grid">
                <div class="info-item">
                    <strong>Inquiry Date:</strong> {{ $request->created_at->format('M d, Y') }}
                </div>
                <div class="info-item">
                    <strong>Response Date:</strong> {{ $request->response_sent_at ? $request->response_sent_at->format('M d, Y') : 'N/A' }}
                </div>
                <div class="info-item">
                    <strong>Status:</strong> <span class="status-responded">Responded</span>
                </div>
                @if($respondedBy)
                <div class="info-item">
                    <strong>Responded By:</strong> {{ $respondedBy->name }}
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Plant Availability -->
    <div class="info-card">
        <div class="info-card-header">
            <h6><i class="fas fa-leaf"></i>Plant Availability</h6>
        </div>
        <div class="info-card-body no-padding">
            <div class="plant-table-wrapper">
                <table class="plant-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Plant Name</th>
                            <th>Qty</th>
                            <th>Height</th>
                            <th>Spread</th>
                            <th>Spacing</th>
                            <th>Availability</th>
                            @if($request->request_type === 'client')
                            <th>Unit Price</th>
                            <th>Total</th>
                            @endif
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td class="plant-name">{{ $item['plant_name'] ?? $item['name'] ?? 'N/A' }}</td>
                            <td>{{ $item['quantity'] ?? 0 }}</td>
                            <td>{{ $item['height_mm'] ?? $item['height'] ?? 'N/A' }}</td>
                            <td>{{ $item['spread_mm'] ?? $item['spread'] ?? 'N/A' }}</td>
                            <td>{{ $item['spacing_mm'] ?? $item['spacing'] ?? 'N/A' }}</td>
                            <td>
                                @php
                                    $availability = $item['availability'] ?? 'N/A';
                                @endphp
                                @if($availability === 'Available')
                                    <span class="avail-badge avail-success">Available</span>
                                @elseif($availability === 'Limited Stock')
                                    <span class="avail-badge avail-warning">Limited Stock</span>
                                @elseif($availability === 'Out of Stock')
                                    <span class="avail-badge avail-danger">Out of Stock</span>
                                @elseif($availability === 'Pre-order')
                                    <span class="avail-badge avail-purple">Pre-order</span>
                                @else
                                    <span class="avail-badge avail-secondary">{{ $availability }}</span>
                                @endif
                            </td>
                            @if($request->request_type === 'client')
                            <td class="price-col">
                                @if(!empty($item['unit_price']) && $item['unit_price'] != 0)
                                    ₱{{ number_format($item['unit_price'], 2) }}
                                @else
                                    —
                                @endif
                            </td>
                            <td class="price-col">
                                @if(!empty($item['total_price']) && $item['total_price'] != 0)
                                    ₱{{ number_format($item['total_price'], 2) }}
                                @else
                                    —
                                @endif
                            </td>
                            @endif
                            <td class="remarks">
                                {{ !empty($item['remarks']) ? $item['remarks'] : '—' }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Help Text -->
    <div class="help-box">
        <i class="fas fa-info-circle"></i>
        <strong>Next Steps:</strong> Our team will contact you via email or phone to finalize your order. 
        If you have any questions, please reply to the email or contact us directly.
    </div>
</div>

<style>
/* Reset and Container */
.response-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

/* Header */
.response-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    flex-wrap: wrap;
    gap: 15px;
}

.header-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.response-title {
    margin: 0;
    font-size: 1.5rem;
    color: #2d3748;
    font-weight: 600;
}

.response-title i {
    color: #198754;
    margin-right: 10px;
}

.download-btn {
    padding: 10px 20px;
    background: #198754;
    color: white;
    text-decoration: none;
    border-radius: 8px;
    font-size: 0.9rem;
    transition: background 0.3s;
}

.download-btn:hover {
    background: #146c43;
    color: white;
}

.download-btn i {
    margin-right: 8px;
}

.back-btn {
    padding: 10px 20px;
    background: #6c757d;
    color: white;
    text-decoration: none;
    border-radius: 8px;
    font-size: 0.9rem;
    transition: background 0.3s;
}

.back-btn:hover {
    background: #5a6268;
    color: white;
}

.back-btn i {
    margin-right: 8px;
}

/* Info Card */
.info-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    margin-bottom: 20px;
    overflow: hidden;
}

.info-card-header {
    background: #f8f9fa;
    padding: 15px 20px;
    border-bottom: 1px solid #e9ecef;
}

.info-card-header h6 {
    margin: 0;
    font-size: 1rem;
    font-weight: 600;
    color: #2d3748;
}

.info-card-header i {
    color: #198754;
    margin-right: 8px;
}

.info-card-body {
    padding: 20px;
}

.info-card-body.no-padding {
    padding: 0;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 15px;
}

.info-item {
    font-size: 0.95rem;
    color: #495057;
}

.info-item strong {
    color: #2d3748;
}

.status-responded {
    display: inline-block;
    padding: 4px 12px;
    background: #d1f0dd;
    color: #198754;
    border-radius: 12px;
    font-size: 0.85rem;
    font-weight: 600;
}

/* Plant Table - Custom, No Bootstrap */
.plant-table-wrapper {
    width: 100%;
    overflow-x: auto;
}

.plant-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.9rem;
}

.plant-table thead {
    background: #198754;
    color: white;
}

.plant-table thead th {
    padding: 12px 15px;
    text-align: left;
    font-weight: 600;
    font-size: 0.85rem;
    white-space: nowrap;
}

.plant-table tbody tr {
    border-bottom: 1px solid #e9ecef;
}

.plant-table tbody tr:hover {
    background: #f8f9fa;
}

.plant-table tbody td {
    padding: 12px 15px;
    color: #495057;
}

.plant-table tbody td:first-child {
    font-weight: 600;
    color: #198754;
}

.plant-table .plant-name {
    font-weight: 600;
    color: #2d3748;
}

.plant-table .remarks {
    font-size: 0.85rem;
    color: #6c757d;
}

.plant-table .price-col {
    text-align: right;
    font-weight: 600;
    color: #198754;
}

/* Availability Badges */
.avail-badge {
    display: inline-block;
    padding: 5px 12px;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 600;
    white-space: nowrap;
}

.avail-success {
    background: #d1f0dd;
    color: #198754;
}

.avail-warning {
    background: #fff3cd;
    color: #856404;
}

.avail-danger {
    background: #f8d7da;
    color: #721c24;
}

.avail-purple {
    background: #e7d6f5;
    color: #6f42c1;
}

.avail-secondary {
    background: #e9ecef;
    color: #6c757d;
}

/* Help Box */
.help-box {
    background: #d1ecf1;
    border: 1px solid #bee5eb;
    border-radius: 8px;
    padding: 15px 20px;
    color: #0c5460;
    font-size: 0.9rem;
}

.help-box i {
    margin-right: 8px;
}

/* Scrollbar */
.plant-table-wrapper::-webkit-scrollbar {
    height: 8px;
}

.plant-table-wrapper::-webkit-scrollbar-track {
    background: #f1f1f1;
}

.plant-table-wrapper::-webkit-scrollbar-thumb {
    background: #198754;
    border-radius: 4px;
}

/* Mobile */
@media (max-width: 768px) {
    .response-container {
        padding: 15px;
    }
    
    .response-title {
        font-size: 1.2rem;
    }
    
    .plant-table {
        font-size: 0.8rem;
    }
    
    .plant-table thead th,
    .plant-table tbody td {
        padding: 10px 12px;
    }
}
</style>
@endsection
