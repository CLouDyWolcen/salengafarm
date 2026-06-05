@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2><i class="fas fa-clipboard-list me-2"></i>Audit Trail</h2>
            <p class="text-muted mb-0">System activity and security monitoring</p>
        </div>
        <a href="{{ route('dashboard') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h6 class="card-title">Total Logs Today</h6>
                    <h2 class="mb-0">{{ $stats['total_logs'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h6 class="card-title">Active Users Today</h6>
                    <h2 class="mb-0">{{ $stats['unique_users'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h6 class="card-title">Failed Logins Today</h6>
                    <h2 class="mb-0">{{ $stats['failed_logins'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <h6 class="card-title">Critical Actions Today</h6>
                    <h2 class="mb-0">{{ $stats['critical_actions'] }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.audit-logs.index') }}" id="filterForm">
                <div class="row g-3">
                    <!-- Date Range Filter -->
                    <div class="col-md-3">
                        <label class="form-label">Date Range</label>
                        <select name="date_range" class="form-select" onchange="toggleCustomDates()">
                            <option value="today" {{ $dateRange == 'today' ? 'selected' : '' }}>Today</option>
                            <option value="last_7_days" {{ $dateRange == 'last_7_days' ? 'selected' : '' }}>Last 7 Days</option>
                            <option value="last_30_days" {{ $dateRange == 'last_30_days' ? 'selected' : '' }}>Last 30 Days</option>
                            <option value="custom" {{ $dateRange == 'custom' ? 'selected' : '' }}>Custom Range</option>
                        </select>
                    </div>

                    <!-- Custom Date Inputs (hidden by default) -->
                    <div class="col-md-3" id="customDatesDiv" style="display: {{ $dateRange == 'custom' ? 'block' : 'none' }};">
                        <label class="form-label">Start Date</label>
                        <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                    </div>
                    <div class="col-md-3" id="customDatesDiv2" style="display: {{ $dateRange == 'custom' ? 'block' : 'none' }};">
                        <label class="form-label">End Date</label>
                        <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                    </div>

                    <!-- User Filter -->
                    <div class="col-md-3">
                        <label class="form-label">User</label>
                        <select name="user_id" class="form-select">
                            <option value="">All Users</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ $userId == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Action Type Filter -->
                    <div class="col-md-3">
                        <label class="form-label">Action Type</label>
                        <select name="action_type" class="form-select">
                            <option value="">All Actions</option>
                            @foreach($actionTypes as $type)
                                <option value="{{ $type }}" {{ $actionType == $type ? 'selected' : '' }}>
                                    {{ $type }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Entity Type Filter -->
                    <div class="col-md-3">
                        <label class="form-label">Entity Type</label>
                        <select name="entity_type" class="form-select">
                            <option value="">All Entities</option>
                            @foreach($entityTypes as $type)
                                <option value="{{ $type }}" {{ $entityType == $type ? 'selected' : '' }}>
                                    {{ $type }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Search -->
                    <div class="col-md-3">
                        <label class="form-label">Search</label>
                        <input type="text" name="search" class="form-control" placeholder="Email, IP, Action..." value="{{ $search }}">
                    </div>

                    <!-- Filter Buttons -->
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-filter me-1"></i>Apply Filters
                        </button>
                        <a href="{{ route('admin.audit-logs.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-1"></i>Clear
                        </a>
                    </div>
                </div>
            </form>

            <!-- Export Button -->
            <div class="mt-3">
                <a href="{{ route('admin.audit-logs.export') }}?{{ http_build_query(request()->all()) }}" class="btn btn-success">
                    <i class="fas fa-file-csv me-2"></i>Export to CSV
                </a>
            </div>
        </div>
    </div>

    <!-- Audit Logs Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Audit Logs ({{ $logs->total() }} records)</h5>
        </div>
        <div class="card-body">
            @if($logs->isEmpty())
                <p class="text-center text-muted py-4">No audit logs found matching your criteria.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Timestamp</th>
                                <th>User</th>
                                <th>Action</th>
                                <th>Entity</th>
                                <th>IP Address</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($logs as $log)
                                <tr class="border-start border-5 border-{{ $log->getColorClass() }}">
                                    <td>
                                        <small>{{ $log->created_at->format('M d, Y') }}</small><br>
                                        <small class="text-muted">{{ $log->created_at->format('h:i A') }}</small>
                                    </td>
                                    <td>
                                        <div>{{ $log->user_email }}</div>
                                        <span class="badge bg-secondary">{{ ucfirst($log->user_role) }}</span>
                                    </td>
                                    <td>
                                        <strong>{{ $log->action }}</strong>
                                        @if($log->isCritical())
                                            <i class="fas fa-exclamation-triangle text-danger ms-1" title="Critical Action"></i>
                                        @endif
                                        @if($log->isFailedLogin())
                                            <i class="fas fa-lock text-warning ms-1" title="Failed Login"></i>
                                        @endif
                                    </td>
                                    <td>
                                        @if($log->entity_type)
                                            {{ $log->entity_type }}
                                            @if($log->entity_id)
                                                #{{ $log->entity_id }}
                                            @endif
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td><code>{{ $log->ip_address }}</code></td>
                                    <td>
                                        <button class="btn btn-sm btn-info" onclick="viewLogDetails({{ $log->id }})">
                                            <i class="fas fa-eye"></i> View Details
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $logs->appends(request()->all())->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Log Details Modal -->
<div class="modal fade" id="logDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-info-circle me-2"></i>Audit Log Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="logDetailsContent">
                <div class="text-center py-4">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleCustomDates() {
    const dateRange = document.querySelector('select[name="date_range"]').value;
    const customDiv = document.getElementById('customDatesDiv');
    const customDiv2 = document.getElementById('customDatesDiv2');
    
    if (dateRange === 'custom') {
        customDiv.style.display = 'block';
        customDiv2.style.display = 'block';
    } else {
        customDiv.style.display = 'none';
        customDiv2.style.display = 'none';
    }
}

function viewLogDetails(logId) {
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('logDetailsModal'));
    modal.show();
    
    // Fetch log details
    fetch(`/admin/audit-logs/${logId}`)
        .then(response => response.json())
        .then(data => {
            const log = data.log;
            
            let html = `
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>User:</strong><br>
                        ${log.user_email}<br>
                        <span class="badge bg-secondary">${log.user_role}</span>
                    </div>
                    <div class="col-md-6">
                        <strong>Timestamp:</strong><br>
                        ${new Date(log.created_at).toLocaleString()}
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Action:</strong><br>
                        ${log.action}
                    </div>
                    <div class="col-md-6">
                        <strong>Entity:</strong><br>
                        ${log.entity_type ? log.entity_type + (log.entity_id ? ' #' + log.entity_id : '') : 'N/A'}
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>IP Address:</strong><br>
                        <code>${log.ip_address}</code>
                    </div>
                    <div class="col-md-6">
                        <strong>URL:</strong><br>
                        <small class="text-break">${log.url || 'N/A'}</small>
                    </div>
                </div>
                
                <div class="mb-3">
                    <strong>User Agent:</strong><br>
                    <small class="text-muted">${log.user_agent || 'N/A'}</small>
                </div>
            `;
            
            // Show changes if available
            if (log.old_values || log.new_values) {
                html += `
                    <hr>
                    <h6>Changes:</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Before:</strong>
                            <pre class="bg-light p-2 rounded"><code>${log.old_values ? JSON.stringify(log.old_values, null, 2) : 'N/A'}</code></pre>
                        </div>
                        <div class="col-md-6">
                            <strong>After:</strong>
                            <pre class="bg-light p-2 rounded"><code>${log.new_values ? JSON.stringify(log.new_values, null, 2) : 'N/A'}</code></pre>
                        </div>
                    </div>
                    <p class="text-muted"><strong>Summary:</strong> ${data.changes_description}</p>
                `;
            }
            
            document.getElementById('logDetailsContent').innerHTML = html;
        })
        .catch(error => {
            document.getElementById('logDetailsContent').innerHTML = `
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle me-2"></i>Failed to load log details.
                </div>
            `;
        });
}
</script>
@endsection
