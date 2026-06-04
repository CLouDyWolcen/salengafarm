@forelse($users as $index => $user)
<tr data-user-id="{{ $user->id }}">
    <td style="text-align: center;">
        <input type="checkbox" class="form-check-input user-checkbox" value="{{ $user->id }}" style="width: 18px; height: 18px; cursor: pointer;">
    </td>
    <td>{{ $index + 1 }}</td>
    <td>{{ $user->first_name }}</td>
    <td>{{ $user->last_name }}</td>
    <td>{{ $user->email }}</td>
    <td>{{ $user->contact_number ?? 'N/A' }}</td>
    <td>
        @php
            $badgeColor = match($user->role) {
                'super_admin' => 'bg-warning text-dark',
                'admin' => 'bg-danger',
                'client' => 'bg-success',
                default => 'bg-secondary'
            };
        @endphp
        <span class="badge {{ $badgeColor }}">
            {{ ucfirst(str_replace('_', ' ', $user->role)) }}
        </span>
    </td>
    <td class="text-end">
        <div class="btn-group" role="group">
            <button type="button" class="btn btn-link text-info p-0 view-user-btn" 
                    data-user-id="{{ $user->id }}"
                    data-user-data="{{ json_encode($user) }}"
                    title="View Details">
                <i class="fas fa-eye"></i>
            </button>
            <a href="{{ route('users.edit', $user) }}" class="btn btn-link text-success p-0" title="Edit">
                <i class="fas fa-edit"></i>
            </a>
            <button type="button" class="btn btn-link text-danger p-0 delete-user-btn" 
                    data-user-id="{{ $user->id }}" 
                    data-user-name="{{ $user->first_name }} {{ $user->last_name }}"
                    title="Delete">
                <i class="fas fa-trash"></i>
            </button>
        </div>
        <form id="delete-user-form-{{ $user->id }}" action="{{ route('users.destroy', $user) }}" method="POST" class="d-none">
            @csrf
            @method('DELETE')
        </form>
    </td>
</tr>
@empty
<tr>
    <td colspan="8" class="text-center py-4">
        <i class="fas fa-users fa-3x text-muted mb-3"></i>
        <p class="text-muted">No users found</p>
    </td>
</tr>
@endforelse
