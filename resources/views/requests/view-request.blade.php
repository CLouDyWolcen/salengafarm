<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Request Details #{{ $request->id }} - Salenga Farm</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('tree-leaf.ico') }}?v=2">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('tree-leaf.ico') }}?v=2">
    <link rel="icon" type="image/ico" href="{{ asset('tree-leaf.ico') }}?v=2">
    <link rel="apple-touch-icon" href="{{ asset('tree-leaf.ico') }}?v=2">
    <meta name="msapplication-TileImage" content="{{ asset('tree-leaf.ico') }}?v=2">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <link href="{{ asset('css/sidebar.css') }}" rel="stylesheet">
    <link href="{{ asset('css/dashboard.css') }}?v=4" rel="stylesheet">
    <link href="{{ asset('css/push-notifications.css') }}?v={{ time() }}" rel="stylesheet">
    
    <style>
        /* Custom Request Details Styling - Override Bootstrap */
        .request-details-container {
            background: #f8f9fa;
            min-height: 100vh;
            padding: 10px;
        }
        
        .request-header {
            background: white;
            padding: 10px 15px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .request-header h2 {
            margin: 0;
            color: #2d3748;
            font-size: 1.3rem;
            font-weight: 600;
        }
        
        .request-header .header-actions {
            display: flex;
            gap: 10px;
        }
        
        /* Custom Grid Layout - No Bootstrap */
        .info-cards-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-bottom: 10px;
        }
        
        @media (max-width: 768px) {
            .info-cards-grid {
                grid-template-columns: 1fr;
            }
        }
        
        .items-section {
            width: 100%;
        }
        
        .custom-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            overflow: hidden;
        }
        
        .custom-card-header {
            background: linear-gradient(135deg, #198754 0%, #146c43 100%);
            color: white;
            padding: 8px 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: none;
        }
        
        .custom-card-header h5 {
            margin: 0;
            font-size: 0.95rem;
            font-weight: 600;
        }
        
        .custom-card-header .edit-btn {
            background: rgba(255,255,255,0.2);
            border: 1px solid rgba(255,255,255,0.3);
            color: white;
            padding: 3px 8px;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 0.85rem;
        }
        
        .custom-card-header .edit-btn:hover {
            background: rgba(255,255,255,0.3);
            transform: translateY(-1px);
        }
        
        .custom-card-body {
            padding: 10px;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 6px 10px;
            border-bottom: 1px solid #e9ecef;
        }
        
        .info-row:last-child {
            border-bottom: none;
        }
        
        .info-row:hover {
            background: #f8f9fa;
        }
        
        .info-label {
            font-weight: 600;
            color: #495057;
            font-size: 0.85rem;
        }
        
        .info-value {
            color: #2d3748;
            font-size: 0.85rem;
        }
        
        .status-badge {
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .status-pending {
            background: #fff3cd;
            color: #856404;
        }
        
        .status-sent {
            background: #d1f0dd;
            color: #198754;
        }
        
        .status-responded {
            background: #cfe2ff;
            color: #0d6efd;
        }
        
        .status-cancelled {
            background: #f8d7da;
            color: #721c24;
        }
        
        .pricing-section {
            background: white;
            padding: 8px 12px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .pricing-label {
            font-weight: 600;
            color: #495057;
            font-size: 0.85rem;
        }
        
        .pricing-select {
            border: 2px solid #e9ecef;
            border-radius: 5px;
            padding: 5px 25px 5px 8px;
            font-size: 0.85rem;
            transition: all 0.3s;
        }
        
        .pricing-select:focus {
            border-color: #198754;
            box-shadow: 0 0 0 3px rgba(25,135,84,0.1);
        }
        
        .action-buttons {
            display: flex;
            gap: 6px;
            padding: 8px 10px;
            background: #f8f9fa;
        }
        
        .action-btn {
            flex: 1;
            padding: 6px 12px;
            border: none;
            border-radius: 5px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            font-size: 0.85rem;
        }
        
        .action-btn-success {
            background: #198754;
            color: white;
        }
        
        .action-btn-success:hover {
            background: #146c43;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(25,135,84,0.3);
        }
        
        .action-btn-primary {
            background: #0d6efd;
            color: white;
        }
        
        .action-btn-primary:hover {
            background: #0b5ed7;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(13,110,253,0.3);
        }
        
        .action-btn-warning {
            background: #ffc107;
            color: #000;
        }
        
        .action-btn-warning:hover {
            background: #ffca2c;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(255,193,7,0.3);
        }
        
        .response-alert {
            background: #d1f0dd;
            border: 1px solid #badbcc;
            color: #0f5132;
            padding: 8px 10px;
            border-radius: 5px;
            margin: 8px 10px;
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 0.85rem;
        }
        
        .items-table-container {
            overflow-x: auto;
            padding: 0;
        }
        
        .items-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.85rem;
        }
        
        .items-table thead {
            background: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
        }
        
        .items-table th {
            padding: 8px 8px;
            text-align: left;
            font-weight: 600;
            color: #495057;
            white-space: nowrap;
            font-size: 0.8rem;
        }
        
        .items-table tbody tr {
            border-bottom: 1px solid #e9ecef;
            transition: background 0.2s;
        }
        
        .items-table tbody tr:hover {
            background: #f8f9fa;
        }
        
        .items-table td {
            padding: 8px 8px;
            color: #2d3748;
            font-size: 0.8rem;
        }
        
        .items-table th:first-child,
        .items-table td:first-child {
            padding-left: 15px;
        }
        
        .items-table th:last-child,
        .items-table td:last-child {
            padding-right: 15px;
        }
    </style>
</head>
<body class="bg-light">
    <div id="sidebarOverlay"></div>
    <div class="dashboard-flex">
        @include('layouts.sidebar')
        <!-- Sidebar Toggle Button for Mobile -->
        <button id="sidebarToggle" class="btn btn-success d-lg-none" type="button" aria-label="Open sidebar">
            <i class="fa fa-bars" style="font-size: 1.3rem;"></i>
        </button>
        <div class="main-content">
            <div class="request-details-container">
                    <!-- Header -->
                    <div class="request-header">
                        <h2>Request Details #{{ $request->id }}</h2>
                        <div class="header-actions">
                            <a href="{{ route('requests.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-1"></i>Back to List
                            </a>
                            <button id="printRequestBtn" class="btn btn-outline-primary">
                                <i class="fas fa-print me-1"></i>Print
                            </button>
                        </div>
                    </div>

                        @if(session('success') || session('error') || $errors->any())
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                @if(session('success'))
                                    if (window.PushNotifications) {
                                        window.PushNotifications.show('success', '{{ session('success') }}', true);
                                    }
                                @endif
                                
                                @if(session('error'))
                                    if (window.PushNotifications) {
                                        window.PushNotifications.show('danger', '{{ session('error') }}', false);
                                    }
                                @endif
                                
                                @if($errors->any())
                                    if (window.PushNotifications) {
                                        @foreach($errors->all() as $error)
                                            window.PushNotifications.show('danger', '{{ $error }}', false);
                                        @endforeach
                                    }
                                @endif
                            });
                        </script>
                        @endif

                        <!-- Pricing Options - Only for Client Requests -->
                        @if($request->request_type == 'client')
                        <div class="pricing-section">
                            <span class="pricing-label">Pricing Options:</span>
                            <select class="pricing-select" id="pricingOptions">
                                <option value="None" {{ $request->pricing == 'None' ? 'selected' : '' }}>None</option>
                                <option value="Low cost" {{ $request->pricing == 'Low cost' ? 'selected' : '' }}>Low cost</option>
                                <option value="High cost" {{ $request->pricing == 'High cost' ? 'selected' : '' }}>High cost</option>
                            </select>
                        </div>
                        @endif

                        <!-- Info Cards Grid: Request Info and Client Info side by side -->
                        <div class="info-cards-grid">
                            <!-- Request Information Card -->
                            <div class="custom-card">
                                <div class="custom-card-header">
                                    <h5>Request Information</h5>
                                    <button class="edit-btn edit-request-info-btn" title="Edit Request Information">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </div>
                                <div style="padding: 0;">
                                    <div class="info-row">
                                        <span class="info-label">Status</span>
                                        <span class="info-value">
                                            @if($request->status == 'pending')
                                                <span class="status-badge status-pending">Pending</span>
                                            @elseif($request->status == 'sent' || $request->status == 'responded')
                                                <span class="status-badge status-responded">Responded</span>
                                            @else
                                                <span class="status-badge status-cancelled">Cancelled</span>
                                            @endif
                                        </span>
                                    </div>
                                    <div class="info-row">
                                        <span class="info-label">Request Date</span>
                                        <span class="info-value">{{ $request->request_date->format('M d, Y') }}</span>
                                    </div>
                                    <div class="info-row">
                                        <span class="info-label">Due Date</span>
                                        <span class="info-value">{{ $request->due_date->format('M d, Y') }}</span>
                                    </div>
                                    <div class="info-row">
                                        <span class="info-label">Total Items</span>
                                        <span class="info-value">{{ count($items) }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Client/User Information Card -->
                            <div class="custom-card">
                                <div class="custom-card-header">
                                    <h5>@if($request->request_type == 'user')User Information @else Client Information @endif</h5>
                                    <button class="edit-btn edit-client-info-btn" title="@if($request->request_type == 'user')Edit User Information @else Edit Client Information @endif">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </div>
                                <div class="custom-card-body">
                                    <div style="margin-bottom: 15px;">
                                        <div class="info-label" style="margin-bottom: 5px;">Name</div>
                                        <div class="info-value">{{ htmlspecialchars($request->name) }}</div>
                                    </div>
                                    <div>
                                        <div class="info-label" style="margin-bottom: 5px;">Email</div>
                                        <div class="info-value">{{ htmlspecialchars($request->email) }}</div>
                                    </div>
                                </div>
                                    @if($request->status == 'pending')
                                        <div class="action-buttons">
                                            <form action="{{ route('requests.send-email', $request->id) }}" method="POST" style="flex: 1; margin: 0;">
                                                @csrf
                                                <button type="submit" class="action-btn action-btn-success" id="sendEmailBtn">
                                                    <i class="fas fa-envelope"></i>
                                                    @if($request->request_type == 'user')
                                                        Send Email to User
                                                    @else
                                                        Send Email to Client
                                                    @endif
                                                </button>
                                            </form>
                                            
                                            <button type="button" class="action-btn action-btn-primary" id="sendResponseBtn" data-bs-toggle="modal" data-bs-target="#sendResponseModal">
                                                <i class="fas fa-paper-plane"></i>
                                                @if($request->request_type == 'user')
                                                    Send Response to User
                                                @else
                                                    Send Response to Client
                                                @endif
                                            </button>
                                        </div>
                                    @elseif($request->status == 'sent')
                                        <div class="action-buttons">
                                            <form action="{{ route('requests.send-email', $request->id) }}" method="POST" style="flex: 1; margin: 0;">
                                                @csrf
                                                <button type="submit" class="action-btn action-btn-warning" id="resendEmailBtn">
                                                    <i class="fas fa-redo"></i>
                                                    @if($request->request_type == 'user')
                                                        Resend Email to User
                                                    @else
                                                        Resend Email to Client
                                                    @endif
                                                </button>
                                            </form>
                                            
                                            <button type="button" class="action-btn action-btn-primary" id="sendResponseBtn2" data-bs-toggle="modal" data-bs-target="#sendResponseModal">
                                                <i class="fas fa-redo"></i>
                                                @if($request->request_type == 'user')
                                                    Resend Response to User
                                                @else
                                                    Resend Response to Client
                                                @endif
                                            </button>
                                        </div>
                                    @elseif($request->status == 'responded')
                                        <div class="response-alert">
                                            <i class="fas fa-check-circle"></i>
                                            Response sent on {{ $request->response_sent_at ? \Carbon\Carbon::parse($request->response_sent_at)->format('M d, Y') : 'N/A' }}
                                        </div>
                                        
                                        <div class="action-buttons mt-3">
                                            <form action="{{ route('requests.send-email', $request->id) }}" method="POST" style="flex: 1; margin: 0;">
                                                @csrf
                                                <button type="submit" class="action-btn action-btn-warning" id="resendEmailBtn3">
                                                    <i class="fas fa-redo"></i>
                                                    @if($request->request_type == 'user')
                                                        Resend Email to User
                                                    @else
                                                        Resend Email to Client
                                                    @endif
                                                </button>
                                            </form>
                                            
                                            <button type="button" class="action-btn action-btn-primary" id="sendResponseBtn3" data-bs-toggle="modal" data-bs-target="#sendResponseModal">
                                                <i class="fas fa-redo"></i>
                                                @if($request->request_type == 'user')
                                                    Resend Response to User
                                                @else
                                                    Resend Response to Client
                                                @endif
                                            </button>
                                        </div>
                                    @endif
                            </div>
                        </div>

                        <!-- Requested Items Section - Full Width Below -->
                        <div class="items-section">
                            <div class="custom-card">
                                <div class="custom-card-header">
                                    <h5><i class="fas fa-leaf me-2"></i>Requested Items</h5>
                                    <button class="edit-btn edit-items-btn" title="Edit Items">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </div>
                                <div style="padding: 0;">
                                    <div class="items-table-container">
                                        <table class="items-table">
                                            <thead>
                                                <tr>
                                                    <th style="text-align: center; width: 50px;">#</th>
                                                    <th style="text-align: center; width: 60px;">Qty</th>
                                                    <th style="min-width: 150px;">Plant Name</th>
                                                    <th style="text-align: center; width: 80px;">Code</th>
                                                    <th style="text-align: center; width: 90px;">Height<br><small>(mm)</small></th>
                                                    <th style="text-align: center; width: 90px;">Spread<br><small>(mm)</small></th>
                                                    <th style="text-align: center; width: 90px;">Spacing<br><small>(mm)</small></th>
                                                    <th style="min-width: 120px;">Remarks</th>
                                                    <th style="text-align: center; width: 120px;">Availability</th>
                                                    @if($request->request_type == 'client')
                                                    <th style="text-align: center; width: 100px;">Unit Price</th>
                                                    <th style="text-align: center; width: 100px;">Total</th>
                                                    @endif
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($items as $index => $item)
                                                    @php
                                                        $remarks = $item['remarks'] ?? '';
                                                        $availability = $item['availability'] ?? 'Available';
                                                    @endphp
                                                    <tr>
                                                        <td style="text-align: center; font-weight: 600; color: #198754;">{{ $index + 1 }}</td>
                                                        <td style="text-align: center;">{{ $item['quantity'] ?? 1 }}</td>
                                                        <td style="font-weight: 500;">{{ htmlspecialchars($item['name'] ?? 'N/A') }}</td>
                                                        <td style="text-align: center;">{{ htmlspecialchars($item['code'] ?? '—') }}</td>
                                                        <td style="text-align: center;">{{ !empty($item['height']) ? $item['height'] : '—' }}</td>
                                                        <td style="text-align: center;">{{ !empty($item['spread']) ? $item['spread'] : '—' }}</td>
                                                        <td style="text-align: center;">{{ !empty($item['spacing']) ? $item['spacing'] : '—' }}</td>
                                                        <td style="font-size: 0.85rem;">{{ htmlspecialchars($remarks ?: '—') }}</td>
                                                        <td style="text-align: center;">
                                                            @if($availability == 'Available')
                                                                <span class="status-badge status-sent">Available</span>
                                                            @elseif($availability == 'Limited Stock')
                                                                <span class="status-badge status-pending">Limited Stock</span>
                                                            @elseif($availability == 'Out of Stock')
                                                                <span class="status-badge status-cancelled">Out of Stock</span>
                                                            @elseif($availability == 'Pre-order')
                                                                <span class="status-badge status-responded">Pre-order</span>
                                                            @else
                                                                <span style="color: #6c757d;">{{ $availability }}</span>
                                                            @endif
                                                        </td>
                                                        @if($request->request_type == 'client')
                                                        <td style="text-align: center; color: #198754; font-weight: 600;">
                                                            @if(!empty($item['unit_price']) && $item['unit_price'] > 0)
                                                                ₱{{ number_format($item['unit_price'], 2) }}
                                                            @else
                                                                —
                                                            @endif
                                                        </td>
                                                        <td style="text-align: center; color: #198754; font-weight: 700;">
                                                            @if(!empty($item['total_price']) && $item['total_price'] > 0)
                                                                ₱{{ number_format($item['total_price'], 2) }}
                                                            @else
                                                                —
                                                            @endif
                                                        </td>
                                                        @endif
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Email Sending Loading Modal -->
    <div class="modal fade" id="emailLoadingModal" tabindex="-1" aria-labelledby="emailLoadingModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center py-4">
                    <div class="spinner-border text-primary mb-3" role="status" style="width: 3rem; height: 3rem;">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <h5 class="mb-3">Sending Email...</h5>
                    <p class="text-muted mb-3">Please wait while we send the email to <span id="emailRecipient"></span></p>
                    <div class="progress mb-3">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%" id="emailProgress"></div>
                    </div>
                    <button type="button" class="btn btn-outline-danger" id="cancelEmailBtn">
                        <i class="fas fa-times me-1"></i>Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Request Information Modal -->
    <div class="modal fade" id="editRequestInfoModal" tabindex="-1" aria-labelledby="editRequestInfoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editRequestInfoModalLabel">Edit Request Information</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editRequestInfoForm" method="POST" action="{{ route('requests.update-info', $request->id) }}">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="request_date" class="form-label">Request Date</label>
                            <input type="date" class="form-control" id="request_date" name="request_date" value="{{ $request->request_date->format('Y-m-d') }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="due_date" class="form-label">Due Date</label>
                            <input type="date" class="form-control" id="due_date" name="due_date" value="{{ $request->due_date ? $request->due_date->format('Y-m-d') : '' }}">
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="pending" {{ $request->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="responded" {{ $request->status == 'responded' || $request->status == 'sent' ? 'selected' : '' }}>Responded</option>
                                <option value="cancelled" {{ $request->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit User/Client Information Modal -->
    <div class="modal fade" id="editClientInfoModal" tabindex="-1" aria-labelledby="editClientInfoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editClientInfoModalLabel">Edit @if($request->request_type == 'user')User @else Client @endif Information</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editClientInfoForm" method="POST" action="{{ route('requests.update-client', $request->id) }}">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="client_name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="client_name" name="name" value="{{ $request->name }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="client_email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="client_email" name="email" value="{{ $request->email }}" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Items Modal -->
    <div class="modal fade" id="editItemsModal" tabindex="-1" aria-labelledby="editItemsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" style="max-width: 95%; margin: 1.75rem auto;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editItemsModalLabel">Edit Requested Items</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editItemsForm" method="POST" action="{{ route('requests.update-items', $request->id) }}">
                    @csrf
                    <div class="modal-body" style="padding: 15px;">
                        <div class="table-responsive" style="max-height: 500px; overflow-x: auto; overflow-y: auto;">
                            <table class="table table-bordered table-sm" style="font-size: 0.875rem;">
                                <thead class="table-light" style="position: sticky; top: 0; z-index: 10;">
                                    <tr>
                                        <th style="padding: 8px; white-space: nowrap;">Plant Name</th>
                                        <th style="padding: 8px; white-space: nowrap;">Code</th>
                                        <th style="padding: 8px; white-space: nowrap;">Qty</th>
                                        <th style="padding: 8px; white-space: nowrap;">Height (mm)</th>
                                        <th style="padding: 8px; white-space: nowrap;">Spread (mm)</th>
                                        <th style="padding: 8px; white-space: nowrap;">Spacing (mm)</th>
                                        <th style="padding: 8px; white-space: nowrap;">Remarks</th>
                                        <th style="padding: 8px; white-space: nowrap;">Availability</th>
                                        @if($request->request_type == 'client')
                                        <th style="padding: 8px; white-space: nowrap;">Unit Price</th>
                                        <th style="padding: 8px; white-space: nowrap;">Total</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody id="editItemsTableBody">
                                    @foreach($items as $index => $item)
                                        <tr>
                                            <td style="padding: 6px;">
                                                <input type="hidden" name="items[{{ $index }}][name]" value="{{ $item['name'] ?? '' }}">
                                                {{ $item['name'] ?? 'N/A' }}
                                            </td>
                                            <td style="padding: 6px;">
                                                <input type="hidden" name="items[{{ $index }}][code]" value="{{ $item['code'] ?? '' }}">
                                                {{ $item['code'] ?? 'N/A' }}
                                            </td>
                                            <td style="padding: 6px;">
                                                <input type="number" class="form-control form-control-sm" name="items[{{ $index }}][quantity]" value="{{ $item['quantity'] ?? 1 }}" min="1" style="width: 60px; padding: 4px;">
                                            </td>
                                            <td style="padding: 6px;">
                                                <input type="number" class="form-control form-control-sm" name="items[{{ $index }}][height]" value="{{ $item['height'] ?? '' }}" style="width: 80px; padding: 4px;">
                                            </td>
                                            <td style="padding: 6px;">
                                                <input type="number" class="form-control form-control-sm" name="items[{{ $index }}][spread]" value="{{ $item['spread'] ?? '' }}" style="width: 80px; padding: 4px;">
                                            </td>
                                            <td style="padding: 6px;">
                                                <input type="number" class="form-control form-control-sm" name="items[{{ $index }}][spacing]" value="{{ $item['spacing'] ?? '' }}" style="width: 80px; padding: 4px;">
                                            </td>
                                            <td style="padding: 6px;">
                                                <textarea class="form-control form-control-sm" name="items[{{ $index }}][remarks]" rows="2" style="width: 120px; padding: 4px; font-size: 0.8rem;">{{ $item['remarks'] ?? '' }}</textarea>
                                            </td>
                                            <td style="padding: 6px;">
                                                <select class="form-select form-select-sm" name="items[{{ $index }}][availability]" style="width: 110px; padding: 4px; font-size: 0.8rem;">
                                                    <option value="Available" {{ ($item['availability'] ?? 'Available') == 'Available' ? 'selected' : '' }}>Available</option>
                                                    <option value="Limited Stock" {{ ($item['availability'] ?? '') == 'Limited Stock' ? 'selected' : '' }}>Limited Stock</option>
                                                    <option value="Out of Stock" {{ ($item['availability'] ?? '') == 'Out of Stock' ? 'selected' : '' }}>Out of Stock</option>
                                                    <option value="Pre-order" {{ ($item['availability'] ?? '') == 'Pre-order' ? 'selected' : '' }}>Pre-order</option>
                                                </select>
                                            </td>
                                            @if($request->request_type == 'client')
                                            <td style="padding: 6px;">
                                                <input type="number" class="form-control form-control-sm unit-price-input" name="items[{{ $index }}][unit_price]" value="{{ $item['unit_price'] ?? '' }}" step="0.01" style="width: 85px; padding: 4px;" data-index="{{ $index }}">
                                            </td>
                                            <td style="padding: 6px;">
                                                <input type="number" class="form-control form-control-sm total-price-display" name="items[{{ $index }}][total_price]" value="{{ $item['total_price'] ?? '' }}" step="0.01" style="width: 85px; padding: 4px;" readonly>
                                            </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Send Response Confirmation Modal -->
    <div class="modal fade" id="sendResponseModal" tabindex="-1" aria-labelledby="sendResponseModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border: none; border-radius: 15px; overflow: hidden;">
                <div class="modal-header" style="background: linear-gradient(135deg, #2d5016 0%, #3d6b1f 100%); color: white; border: none; padding: 20px 25px;">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <i class="fas fa-paper-plane" style="font-size: 24px;"></i>
                        <h5 class="modal-title" id="sendResponseModalLabel" style="margin: 0; font-weight: 600;">Confirm Send Response</h5>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="padding: 30px 25px;">
                    <div style="text-align: center; margin-bottom: 20px;">
                        <div style="width: 60px; height: 60px; background: #e8f5e9; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px;">
                            <i class="fas fa-check-circle" style="font-size: 30px; color: #2d5016;"></i>
                        </div>
                    </div>
                    <p style="text-align: center; font-size: 16px; color: #333; margin-bottom: 10px;">
                        This will mark the @if($request->request_type == 'user')inquiry @else request @endif as <strong style="color: #2d5016;">responded</strong> and notify the @if($request->request_type == 'user')user @else client @endif.
                    </p>
                    <p style="text-align: center; font-size: 14px; color: #666; margin-bottom: 0;">
                        The @if($request->request_type == 'user')user @else client @endif will receive a notification in their dashboard.
                    </p>
                </div>
                <div class="modal-footer" style="border: none; padding: 15px 25px 25px; justify-content: center; gap: 10px;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="padding: 10px 30px; border-radius: 8px;">
                        <i class="fas fa-times me-1"></i>Cancel
                    </button>
                    <form action="{{ route('requests.send-response', $request->id) }}" method="POST" style="display: inline; margin: 0;">
                        @csrf
                        <button type="submit" class="btn btn-success" id="confirmSendResponseBtn" style="padding: 10px 30px; border-radius: 8px; background: linear-gradient(135deg, #2d5016 0%, #3d6b1f 100%); border: none;">
                            <i class="fas fa-paper-plane me-1"></i>Send Response
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/push-notifications-global.js') }}?v=fadefix{{ time() }}"></script>
    <!-- Add your JavaScript for print functionality and other interactions here -->
    <script>
        $(document).ready(function() {
            // Edit button functionality - show modal forms
            $('.edit-request-info-btn').on('click', function(e) {
                e.preventDefault();
                $('#editRequestInfoModal').modal('show');
            });

            $('.edit-client-info-btn').on('click', function(e) {
                e.preventDefault();
                $('#editClientInfoModal').modal('show');
            });

            $('.edit-items-btn').on('click', function(e) {
                e.preventDefault();
                $('#editItemsModal').modal('show');
            });

            // Auto-calculate total price when unit price or quantity changes
            $(document).on('input', '.unit-price-input', function() {
                const index = $(this).data('index');
                const unitPrice = parseFloat($(this).val()) || 0;
                const quantity = parseInt($('input[name="items[' + index + '][quantity]"]').val()) || 1;
                const totalPrice = unitPrice * quantity;
                $('input[name="items[' + index + '][total_price]"]').val(totalPrice.toFixed(2));
            });

            $(document).on('input', 'input[name*="[quantity]"]', function() {
                const name = $(this).attr('name');
                const match = name.match(/items\[(\d+)\]\[quantity\]/);
                if (match) {
                    const index = match[1];
                    const quantity = parseInt($(this).val()) || 1;
                    const unitPrice = parseFloat($('input[name="items[' + index + '][unit_price]"]').val()) || 0;
                    const totalPrice = unitPrice * quantity;
                    $('input[name="items[' + index + '][total_price]"]').val(totalPrice.toFixed(2));
                }
            });

            // Email sending functionality with loading modal
            let currentEmailRequest = null;
            
            // Handle email sending with loading modal
            $('#sendEmailBtn, #resendEmailBtn, #resendEmailBtn3').on('click', function(e) {
                e.preventDefault();
                
                const form = $(this).closest('form');
                const recipientEmail = '{{ $request->email }}';
                const recipientName = '{{ $request->name }}';
                const isResend = $(this).attr('id') === 'resendEmailBtn' || $(this).attr('id') === 'resendEmailBtn3';
                
                // Show loading modal
                $('#emailRecipient').text(`${recipientName} (${recipientEmail})`);
                $('#emailLoadingModal').modal('show');
                
                // Start progress animation
                startEmailProgress();
                
                // Create FormData for AJAX request
                const formData = new FormData(form[0]);
                
                // Send AJAX request
                currentEmailRequest = $.ajax({
                    url: form.attr('action'),
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        // Complete progress
                        $('#emailProgress').css('width', '100%').removeClass('progress-bar-animated');
                        
                        // Hide modal after brief delay
                        setTimeout(() => {
                            $('#emailLoadingModal').modal('hide');
                            
                            // Show success notification
                            if (window.PushNotifications) {
                                const message = isResend 
                                    ? `Email resent successfully to ${recipientName} (${recipientEmail})!`
                                    : `Email sent successfully to ${recipientName} (${recipientEmail})!`;
                                window.PushNotifications.show('success', message, true);
                            }
                            
                            // Refresh page after short delay to update status
                            setTimeout(() => {
                                location.reload();
                            }, 2000);
                        }, 500);
                    },
                error: function(xhr, status) {
                    // Hide modal
                    $('#emailLoadingModal').modal('hide');
                    
                    // Only show error if it wasn't cancelled
                    if (status !== 'abort') {
                        let errorMessage = 'Failed to send email. Please try again.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        
                        if (window.PushNotifications) {
                            window.PushNotifications.show('danger', errorMessage, false);
                        }
                    }
                },
                    complete: function() {
                        currentEmailRequest = null;
                    }
                });
            });
            
            // Cancel email sending
            $('#cancelEmailBtn').on('click', function() {
                if (currentEmailRequest) {
                    currentEmailRequest.abort();
                    currentEmailRequest = null;
                }
                $('#emailLoadingModal').modal('hide');
                
                if (window.PushNotifications) {
                    window.PushNotifications.show('warning', 'Email sending cancelled.', true);
                }
            });
            
            // Progress animation function
            function startEmailProgress() {
                $('#emailProgress').css('width', '0%').addClass('progress-bar-animated');
                
                let progress = 0;
                const progressInterval = setInterval(() => {
                    progress += Math.random() * 15;
                    if (progress > 90) {
                        progress = 90;
                    }
                    $('#emailProgress').css('width', progress + '%');
                    
                    if (!currentEmailRequest || progress >= 90) {
                        clearInterval(progressInterval);
                    }
                }, 200);
            }
            
            // Reset modal when hidden
            $('#emailLoadingModal').on('hidden.bs.modal', function() {
                $('#emailProgress').css('width', '0%').addClass('progress-bar-animated');
            });

            // Form submissions with loading states
            $('#editRequestInfoForm, #editClientInfoForm, #editItemsForm').on('submit', function(e) {
                const submitBtn = $(this).find('button[type="submit"]');
                submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Saving...');
            });

            // Print button functionality
            $('#printRequestBtn').on('click', function(e) {
                e.preventDefault();
                window.print();
            });

            // Pricing Options dropdown functionality
            $('#pricingOptions').on('change', function() {
                const selectedPricing = $(this).val();
                const requestId = {{ $request->id }};
                
                // Send AJAX request to update pricing
                $.ajax({
                    url: `/requests/update-pricing/${requestId}`,
                    type: 'POST',
                    data: {
                        pricing: selectedPricing,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            AlertSystem.toast({
                                message: 'Pricing option updated successfully!',
                                type: 'success'
                            });
                            // Reload to show updated pricing
                            setTimeout(() => {
                                location.reload();
                            }, 1000);
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'Failed to update pricing option.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        AlertSystem.toast({
                            message: errorMessage,
                            type: 'error'
                        });
                    }
                });
            });
            
            // Send Response confirmation modal - add loading state
            $('#confirmSendResponseBtn').on('click', function(e) {
                const btn = $(this);
                const form = btn.closest('form');
                
                // Prevent double submission
                if (btn.prop('disabled')) {
                    e.preventDefault();
                    return false;
                }
                
                btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Sending...');
                
                // Set a timeout to re-enable button if submission takes too long
                setTimeout(function() {
                    if (btn.prop('disabled')) {
                        btn.prop('disabled', false).html('Send Response');
                        alert('Request timed out. Please try again.');
                    }
                }, 30000); // 30 second timeout
                
                // ACTUALLY SUBMIT THE FORM
                form.submit();
            });
        });
    </script>
</body>
</html>

