@extends('layouts.public')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3">User Plant Request Details</h1>
            <p class="text-muted">Request #{{ $request->id }} from {{ $request->name }}</p>
        </div>
        <div>
            <a href="{{ route('user-requests.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back to Client Inquiry
            </a>
            @if($request->pdf_path)
            <a href="{{ route('user-requests.download-pdf', $request->id) }}" class="btn btn-success ms-2">
                <i class="fas fa-download me-1"></i> Download PDF
            </a>
            @endif
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="row">
        <!-- Request Info -->
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Contact Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="fw-bold">Name:</label>
                        <p>{{ $request->name }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="fw-bold">Email:</label>
                        <p>{{ $request->email }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="fw-bold">Phone:</label>
                        <p>{{ $request->phone ?? 'N/A' }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="fw-bold">Address:</label>
                        <p>{{ $request->address ?? 'N/A' }}</p>
                    </div>
                    @if($request->message)
                    <div class="mb-3">
                        <label class="fw-bold">Additional Message:</label>
                        <p>{{ $request->message }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Request Details -->
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Request Details</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="fw-bold">Request Date:</label>
                        <p>{{ \Carbon\Carbon::parse($request->request_date)->format('M d, Y g:i A') }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="fw-bold">Status:</label>
                        <p>
                            @if($request->status == 'pending')
                                <span class="badge bg-warning">Pending</span>
                            @elseif($request->status == 'sent')
                                <span class="badge bg-success">Sent</span>
                            @elseif($request->status == 'cancelled')
                                <span class="badge bg-danger">Cancelled</span>
                            @else
                                <span class="badge bg-secondary">{{ $request->status }}</span>
                            @endif
                        </p>
                    </div>
                    @if($request->due_date)
                    <div class="mb-3">
                        <label class="fw-bold">Preferred Delivery Date:</label>
                        <p>{{ \Carbon\Carbon::parse($request->due_date)->format('M d, Y') }}</p>
                    </div>
                    @endif
                    <div class="mb-3">
                        <label class="fw-bold">Request Type:</label>
                        <p>
                            <span class="badge bg-info">User Request</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Admin Actions -->
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Admin Actions</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('requests.update', $request->id) }}" method="POST" class="mb-3">
                        @csrf
                        <div class="mb-3">
                            <label for="status" class="form-label fw-bold">Update Status:</label>
                            <select name="status" id="status" class="form-select">
                                <option value="pending" {{ $request->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="sent" {{ $request->status == 'sent' ? 'selected' : '' }}>Sent</option>
                                <option value="cancelled" {{ $request->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Update Status</button>
                    </form>
                    
                    <hr>
                    
                    <form action="{{ route('requests.destroy', $request->id) }}" method="POST" class="mt-3">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-danger w-100 delete-request-btn" data-request-id="{{ $request->id }}">
                            <i class="fas fa-trash me-1"></i> Delete Request
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Requested Plants -->
    <div class="card mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0">Requested Plants</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Plant Name</th>
                            <th>Plant Code</th>
                            <th>Quantity</th>
                            <th>Height (mm)</th>
                            <th>Spread (mm)</th>
                            <th>Spacing (mm)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(is_array($request->items_json))
                            @foreach($request->items_json as $plant)
                                <tr>
                                    <td>{{ $plant['name'] ?? 'N/A' }}</td>
                                    <td>{{ $plant['code'] ?? 'N/A' }}</td>
                                    <td>{{ $plant['quantity'] ?? 1 }}</td>
                                    <td>{{ $plant['height'] ?? 'N/A' }}</td>
                                    <td>{{ $plant['spread'] ?? 'N/A' }}</td>
                                    <td>{{ $plant['spacing'] ?? 'N/A' }}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6" class="text-center">No plant details available</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this request? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        let formToDelete = null;

        // Delete request button click
        $('.delete-request-btn').on('click', function() {
            formToDelete = $(this).closest('form');
            $('#deleteConfirmModal').modal('show');
        });

        // Confirm delete button click
        $('#confirmDelete').on('click', function() {
            if (formToDelete) {
                formToDelete.submit();
            }
            $('#deleteConfirmModal').modal('hide');
        });
    });
</script>
@endsection 