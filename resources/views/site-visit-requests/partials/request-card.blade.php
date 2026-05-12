<tr class="request-row {{ $request->status }}" data-request-id="{{ $request->id }}">
    <td class="align-middle">
        <strong>{{ $request->user->first_name ?? '' }} {{ $request->user->last_name ?? '' }}</strong>
        @if($request->user->contact_number)
            <br><small class="text-muted"><i class="fas fa-phone me-1"></i>{{ $request->user->contact_number }}</small>
        @endif
    </td>
    <td class="align-middle">
        <small>{{ $request->user->email }}</small>
    </td>
    <td class="align-middle">
        {{ $request->preferred_date->format('M d, Y') }}
        @if($request->preferred_time)
            <br><small class="text-muted">{{ date('g:i A', strtotime($request->preferred_time)) }}</small>
        @endif
    </td>
    <td class="align-middle">
        <small>{{ \Illuminate\Support\Str::limit($request->property_address, 40) }}</small>
    </td>
    <td class="text-center align-middle">
        <span class="badge bg-{{ $request->status_badge_color }}">
            {{ ucfirst($request->status) }}
        </span>
    </td>
    <td class="text-center align-middle">
        <div class="btn-group" role="group">
            <button type="button" class="btn btn-sm btn-outline-success expand-btn" onclick="toggleDetails({{ $request->id }})" title="View Details">
                <i class="fas fa-chevron-down" id="icon-{{ $request->id }}"></i>
            </button>
            @if($request->status === 'pending')
                <button type="button" class="btn btn-sm btn-success" onclick="approveRequest({{ $request->id }})" title="Approve">
                    <i class="fas fa-check"></i>
                </button>
                <button type="button" class="btn btn-sm btn-danger" onclick="rejectRequest({{ $request->id }})" title="Reject">
                    <i class="fas fa-times"></i>
                </button>
            @elseif($request->status === 'approved' && $request->site_visit_id)
                <a href="{{ route('site-visits.edit', $request->site_visit_id) }}" class="btn btn-sm btn-primary" title="View Site Visit">
                    <i class="fas fa-map-marker-alt"></i>
                </a>
            @endif
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteRequest({{ $request->id }})" title="Delete Request">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    </td>
</tr>
<tr class="details-row" id="details-{{ $request->id }}" style="display: none;">
    <td colspan="6" class="p-0">
        <div class="details-content p-3 bg-light">
            <div class="row">
                <div class="col-md-8">
                    <h6 class="text-success mb-3"><i class="fas fa-info-circle me-2"></i>Request Details</h6>
                    
                    <div class="row mb-3">
                        @if($request->property_size)
                        <div class="col-md-6 mb-2">
                            <strong><i class="fas fa-ruler-combined me-2 text-success"></i>Property Size:</strong>
                            <div class="ms-4">{{ $request->property_size }}</div>
                        </div>
                        @endif
                        
                        @if($request->current_condition)
                        <div class="col-md-6 mb-2">
                            <strong><i class="fas fa-info-circle me-2 text-success"></i>Current Condition:</strong>
                            <div class="ms-4">{{ ucwords(str_replace('_', ' ', $request->current_condition)) }}</div>
                        </div>
                        @endif
                    </div>

                    <div class="mb-3">
                        <strong><i class="fas fa-clipboard-list me-2 text-success"></i>Project Description:</strong>
                        <p class="ms-4 mb-0 mt-1">{{ $request->project_description }}</p>
                    </div>

                    @if($request->special_requirements)
                    <div class="mb-3">
                        <strong><i class="fas fa-exclamation-triangle me-2 text-warning"></i>Special Requirements:</strong>
                        <p class="ms-4 mb-0 mt-1">{{ $request->special_requirements }}</p>
                    </div>
                    @endif

                    <div class="mb-2">
                        <small class="text-muted">
                            <i class="fas fa-clock me-1"></i>Requested on {{ $request->created_at->format('M d, Y g:i A') }}
                        </small>
                    </div>

                    @if($request->status !== 'pending')
                        <div class="mt-3 p-3 bg-white rounded border">
                            @if($request->status === 'approved' && $request->admin_notes)
                                <strong class="text-success"><i class="fas fa-check-circle me-2"></i>Admin Notes:</strong>
                                <p class="mb-0 mt-1">{{ $request->admin_notes }}</p>
                            @elseif($request->status === 'rejected' && $request->rejection_reason)
                                <strong class="text-danger"><i class="fas fa-times-circle me-2"></i>Rejection Reason:</strong>
                                <p class="mb-0 mt-1">{{ $request->rejection_reason }}</p>
                            @endif
                            <small class="text-muted d-block mt-2">
                                Reviewed by {{ $request->reviewer->first_name ?? '' }} {{ $request->reviewer->last_name ?? '' }} 
                                on {{ $request->reviewed_at->format('M d, Y g:i A') }}
                            </small>
                            @if($request->status === 'approved' && $request->site_visit_id)
                                <div class="mt-3">
                                    <a href="{{ route('site-visits.edit', $request->site_visit_id) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-map-marker-alt me-2"></i>View Site Visit
                                    </a>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>

                <div class="col-md-4">
                    @if($request->photos && count($request->photos) > 0)
                        <h6 class="text-success mb-3"><i class="fas fa-camera me-2"></i>Property Photos</h6>
                        <div class="d-flex flex-wrap">
                            @foreach($request->photos as $photo)
                                <img src="{{ Storage::url($photo) }}" 
                                     alt="Property Photo" 
                                     class="photo-thumbnail mb-2 me-2"
                                     onclick="viewPhoto('{{ Storage::url($photo) }}')">
                            @endforeach
                        </div>
                    @else
                        <div class="text-muted">
                            <i class="fas fa-image me-2"></i>No photos uploaded
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </td>
</tr>
