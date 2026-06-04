@extends('layouts.app')

@section('title', 'Sales Records - Salenga Farm')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">
            <i class="fas fa-receipt me-2 text-success"></i>Sales Records
        </h2>
        <div class="d-flex gap-2">
            <!-- Export Dropdown -->
            <div class="dropdown">
                <button class="btn btn-success dropdown-toggle" type="button" id="exportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-file-export me-1"></i>Export
                </button>
                <ul class="dropdown-menu" aria-labelledby="exportDropdown">
                    <li>
                        <a class="dropdown-item" href="{{ route('walk-in.export', array_merge(['format' => 'xlsx'], request()->only(['start_date', 'end_date']))) }}">
                            <i class="fas fa-file-excel me-2 text-success"></i>Excel (.xlsx)
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('walk-in.export', array_merge(['format' => 'csv'], request()->only(['start_date', 'end_date']))) }}">
                            <i class="fas fa-file-csv me-2 text-info"></i>CSV (.csv)
                        </a>
                    </li>
                </ul>
            </div>
            
            <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
            </a>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-success">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="fas fa-shopping-cart fa-2x text-success opacity-50"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Total Sales</h6>
                            <h3 class="mb-0">{{ number_format($totalSales) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-primary">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="fas fa-box fa-2x text-primary opacity-50"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Total Quantity Sold</h6>
                            <h3 class="mb-0">{{ number_format($totalQuantity) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-warning">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="fas fa-peso-sign fa-2x text-warning opacity-50"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Total Revenue</h6>
                            <h3 class="mb-0">₱{{ number_format($totalRevenue, 2) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('walk-in.sales-records') }}" class="row g-3">
                <div class="col-md-4">
                    <label for="start_date" class="form-label">Start Date</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" value="{{ request('start_date') }}">
                </div>
                <div class="col-md-4">
                    <label for="end_date" class="form-label">End Date</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request('end_date') }}">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-filter me-2"></i>Filter
                    </button>
                    <a href="{{ route('walk-in.sales-records') }}" class="btn btn-secondary">
                        <i class="fas fa-redo me-2"></i>Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Sales Records Table -->
    <div class="card">
        <div class="card-header bg-white">
            <h5 class="mb-0">Sales Transactions</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Date</th>
                            <th>Plant Name</th>
                            <th>Quantity</th>
                            <th>Unit Price</th>
                            <th>Total Price</th>
                            <th>Customer</th>
                            <th>Payment Method</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sales as $sale)
                        <tr>
                            <td>#{{ $sale->id }}</td>
                            <td>{{ $sale->sale_date ? \Carbon\Carbon::parse($sale->sale_date)->format('M d, Y') : 'N/A' }}</td>
                            <td>
                                <strong>{{ $sale->plant ? $sale->plant->name : 'N/A' }}</strong>
                            </td>
                            <td>{{ $sale->quantity }}</td>
                            <td>₱{{ number_format($sale->price, 2) }}</td>
                            <td><strong>₱{{ number_format($sale->total_price, 2) }}</strong></td>
                            <td>{{ $sale->customer_name ?: 'Walk-in' }}</td>
                            <td>
                                <span class="badge bg-{{ $sale->payment_method === 'cash' ? 'success' : 'primary' }}">
                                    {{ ucfirst($sale->payment_method) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                No sales records found.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($sales->hasPages())
        <div class="card-footer bg-white">
            {{ $sales->links() }}
        </div>
        @endif
    </div>
</div>

<style>
.card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.card-header {
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    padding: 1rem 1.25rem;
}

.table th {
    font-weight: 600;
    font-size: 0.875rem;
    color: #495057;
    border-bottom: 2px solid #dee2e6;
}

.table td {
    vertical-align: middle;
    font-size: 0.875rem;
}

.badge {
    padding: 0.35rem 0.65rem;
    font-weight: 500;
}
</style>
@endsection
