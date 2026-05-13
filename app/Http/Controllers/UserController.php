<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\Notification;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('role', '!=', 'super_admin')->get();
        $roleRequests = \App\Models\RoleRequest::with('user')->orderBy('created_at', 'desc')->get();
        return view('admin.users.index', compact('users', 'roleRequests'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'contact_number' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Automatically set as client (role and is_client flag)
        $validated['role'] = 'client';
        $validated['is_client'] = true;
        $validated['password'] = Hash::make($validated['password']);
        
        // Set default page access for new clients
        // Give them access to all pages by default (same as registration)
        $validated['page_access'] = json_encode(['dashboard', 'plant_guide', 'site_data']);

        User::create($validated);

        return redirect()->route('users.index')->with('success', 'Client account created successfully');
    }

    public function updateRole(User $user, Request $request)
    {
        $request->validate([
            'role' => 'required|in:client,admin',
            'is_client' => 'boolean'
        ]);

        $updateData = ['role' => $request->role];
        
        $wasClient = $user->is_client;

        if ($request->has('is_client')) {
            $updateData['is_client'] = $request->is_client;
        }

        $user->update($updateData);
        
        // If user was just made a client, send notification
        if (!$wasClient && $request->is_client) {
            Notification::create([
                'user_id' => $user->id,
                'type' => 'client_approved',
                'title' => 'Client Access Granted',
                'message' => 'You have been granted client access by an administrator. You now have access to client features.',
                'link' => '/dashboard/user',
                'is_read' => false
            ]);
        }
        
        return redirect()->route('users.index')->with('success', 'User role updated successfully');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        // Log the incoming request data
        Log::info('User update request:', [
            'user_id' => $user->id,
            'request_data' => $request->all(),
            'has_page_access' => $request->has('page_access'),
            'page_access_value' => $request->input('page_access'),
        ]);

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'contact_number' => 'nullable|string|max:255',
            'role' => 'required|in:client,admin',
            'account_type' => 'nullable|in:individual,company',
            'address' => 'nullable|string|max:255',
            'gender' => 'nullable|in:male,female,other',
            'company_name' => 'nullable|string|max:255',
            'company_address' => 'nullable|string|max:255',
            'page_access' => 'nullable|array',
        ]);

        // Handle page access permissions - CRITICAL FIX
        // When no checkboxes are checked, the browser doesn't send page_access at all
        // So we need to explicitly check and set it
        if ($request->has('page_access') && is_array($request->input('page_access'))) {
            // Filter out 'home' since it's always accessible and disabled in form
            $pageAccess = array_filter($request->input('page_access'), function($page) {
                return $page !== 'home';
            });
            $validated['page_access'] = json_encode(array_values($pageAccess));
        } else {
            // No checkboxes were checked (or all were unchecked)
            $validated['page_access'] = json_encode([]);
        }

        // Handle the is_client checkbox explicitly
        $validated['is_client'] = $request->has('is_client');
        
        // If not a client, clear account type fields
        if (!$validated['is_client']) {
            $validated['account_type'] = 'individual';
            $validated['address'] = null;
            $validated['gender'] = null;
            $validated['company_name'] = null;
            $validated['company_address'] = null;
        } else {
            // If client, clear fields based on account type
            if ($validated['account_type'] === 'individual') {
                $validated['company_name'] = null;
                $validated['company_address'] = null;
            } else {
                $validated['address'] = null;
                $validated['gender'] = null;
            }
        }

        // Log the data we're about to save
        Log::info('About to update user with data:', [
            'user_id' => $user->id,
            'validated_data' => $validated,
            'page_access_json' => $validated['page_access'],
        ]);

        $user->update($validated);

        // Log the user after update
        Log::info('User after update:', [
            'user' => $user->fresh()->toArray()
        ]);

        return redirect()->route('users.index')->with('success', 'User updated successfully');
    }
    
    /**
     * Approve a role request
     */
    public function approveRoleRequest($id)
    {
        try {
            $roleRequest = \App\Models\RoleRequest::findOrFail($id);
            $user = $roleRequest->user;
            
            // Update user to client and copy role request data
            $user->is_client = true;
            $user->account_type = $roleRequest->account_type;
            
            // Copy account-type specific fields
            if ($roleRequest->account_type === 'individual') {
                $user->address = $roleRequest->address;
                $user->gender = $roleRequest->gender;
            } else {
                $user->company_name = $roleRequest->company_name;
                $user->company_address = $roleRequest->company_address;
            }
            
            $user->save();
            
            // Update role request status
            $roleRequest->status = 'approved';
            $roleRequest->save();
            
            // Create notification for the user
            Notification::create([
                'user_id' => $user->id,
                'type' => 'client_approved',
                'title' => 'Client Request Approved',
                'message' => 'Your request to become a client has been approved! You now have access to client features.',
                'link' => '/dashboard/user',
                'is_read' => false
            ]);
            
            return redirect()->route('users.index')->with('success', 'Role request approved successfully! User is now a client.')->with('activeTab', 'role-requests');
        } catch (\Exception $e) {
            Log::error('Failed to approve role request: ' . $e->getMessage());
            return redirect()->route('users.index')->with('error', 'Failed to approve role request.')->with('activeTab', 'role-requests');
        }
    }
    
    /**
     * Reject a role request
     */
    public function rejectRoleRequest($id, Request $request)
    {
        try {
            $roleRequest = \App\Models\RoleRequest::findOrFail($id);
            
            // Update role request status
            $roleRequest->status = 'rejected';
            $roleRequest->admin_notes = $request->input('admin_notes');
            $roleRequest->save();
            
            // Create notification for the user
            Notification::create([
                'user_id' => $roleRequest->user_id,
                'type' => 'client_rejected',
                'title' => 'Client Request Rejected',
                'message' => 'Your request to become a client has been reviewed. Please contact us for more information.',
                'link' => '/dashboard/user',
                'is_read' => false
            ]);
            
            return redirect()->route('users.index')->with('success', 'Role request rejected.')->with('activeTab', 'role-requests');
        } catch (\Exception $e) {
            Log::error('Failed to reject role request: ' . $e->getMessage());
            return redirect()->route('users.index')->with('error', 'Failed to reject role request.')->with('activeTab', 'role-requests');
        }
    }
    
    /**
     * Show edit form for role request
     */
    public function editRoleRequest($id)
    {
        $roleRequest = \App\Models\RoleRequest::with('user')->findOrFail($id);
        return view('admin.users.edit-role-request', compact('roleRequest'));
    }
    
    /**
     * Update role request
     */
    public function updateRoleRequest($id, Request $request)
    {
        try {
            $roleRequest = \App\Models\RoleRequest::findOrFail($id);
            
            $roleRequest->update([
                'full_name' => $request->full_name,
                'email' => $request->email,
                'contact_number' => $request->contact_number,
                'gender' => $request->gender,
                'address' => $request->address,
                'company_name' => $request->company_name,
                'company_address' => $request->company_address,
                'status' => $request->status,
                'admin_notes' => $request->admin_notes,
            ]);
            
            // If make_client checkbox is checked, update user
            if ($request->has('make_client')) {
                $user = $roleRequest->user;
                $user->is_client = true;
                $user->save();
            }
            
            return redirect()->route('users.index')->with('success', 'Role request updated successfully!');
        } catch (\Exception $e) {
            Log::error('Failed to update role request: ' . $e->getMessage());
            return redirect()->route('users.index')->with('error', 'Failed to update role request.');
        }
    }
    
    /**
     * Delete role request
     */
    public function deleteRoleRequest($id)
    {
        try {
            $roleRequest = \App\Models\RoleRequest::findOrFail($id);
            $roleRequest->delete();
            
            return redirect()->route('users.index')->with('success', 'Role request removed successfully!')->with('activeTab', 'role-requests');
        } catch (\Exception $e) {
            Log::error('Failed to delete role request: ' . $e->getMessage());
            return redirect()->route('users.index')->with('error', 'Failed to delete role request.')->with('activeTab', 'role-requests');
        }
    }
}