<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Models\PlantRequest;
use App\Models\Notification;
use App\Models\User;

class UserDashboardController extends Controller
{
    /**
     * Show the user/client dashboard (Request Center)
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Fetch recent plant requests by the authenticated user's email
        $requests = PlantRequest::when($user && $user->email, function ($q) use ($user) {
                $q->where('email', $user->email);
            })
            ->orderByDesc('created_at')
            ->limit(15)
            ->get();

        return view('dashboard.user', [
            'user' => $user,
            'requests' => $requests,
        ]);
    }
    
    /**
     * View admin response for an inquiry
     * 
     * @param int $id - Plant request ID
     * @return \Illuminate\View\View
     */
    public function viewResponse($id)
    {
        $user = Auth::user();
        
        // Find the request and verify it belongs to this user
        $request = PlantRequest::where('id', $id)
            ->where('email', $user->email)
            ->firstOrFail();
        
        // Check if response has been sent
        if (!$request->response_sent_at) {
            return redirect()->route('dashboard.user')
                ->with('error', 'This inquiry has not been responded to yet.');
        }
        
        // items_json is already an array due to model casting
        $items = $request->items_json;
        
        // Get admin who responded
        $respondedBy = $request->responded_by ? User::find($request->responded_by) : null;
        
        return view('user.inquiry-response', [
            'request' => $request,
            'items' => $items,
            'respondedBy' => $respondedBy
        ]);
    }
    
    /**
     * Submit client request
     */
    public function submitClientRequest(Request $request)
    {
        try {
            $user = Auth::user();
            
            Log::info('Client request submission started', [
                'user_id' => $user->id,
                'account_type' => $request->account_type,
                'all_data' => $request->all()
            ]);
            
            // Validate based on account type
            $rules = [
                'account_type' => 'required|in:individual,company',
                'message' => 'nullable|string',
            ];
            
            if ($request->account_type === 'individual') {
                $rules['contact_number'] = 'required|string';
                $rules['address'] = 'required|string';
                $rules['gender'] = 'required|in:male,female,other';
            } else {
                $rules['full_name_company'] = 'required|string';
                $rules['contact_number_company'] = 'required|string';
                $rules['gender_company'] = 'required|in:male,female,other';
                $rules['company_name'] = 'required|string';
                $rules['company_address'] = 'nullable|string';
            }
            
            $validated = $request->validate($rules);
            
            Log::info('Validation passed', ['validated' => $validated]);
            
            // Create role request
            $roleRequest = \App\Models\RoleRequest::create([
                'user_id' => $user->id,
                'account_type' => $request->account_type,
                'full_name' => $request->account_type === 'individual' 
                    ? $user->first_name . ' ' . $user->last_name 
                    : $request->full_name_company,
                'contact_number' => $request->account_type === 'individual' 
                    ? $request->contact_number 
                    : $request->contact_number_company,
                'email' => $user->email,
                'address' => $request->account_type === 'individual' ? $request->address : null,
                'gender' => $request->account_type === 'individual' ? $request->gender : $request->gender_company,
                'company_name' => $request->account_type === 'company' ? $request->company_name : null,
                'company_address' => $request->account_type === 'company' ? $request->company_address : null,
                'message' => $request->message,
                'status' => 'pending',
            ]);
            
            Log::info('Role request created', ['role_request_id' => $roleRequest->id]);
            
            // Create notification for super admins only about new role request
            $superAdmins = User::where('role', 'super_admin')->get();
            foreach ($superAdmins as $superAdmin) {
                Notification::create([
                    'user_id' => $superAdmin->id,
                    'type' => 'new_role_request',
                    'title' => 'New Role Request',
                    'message' => "{$user->first_name} {$user->last_name} requested to become a client",
                    'link' => '/users',
                    'is_read' => false
                ]);
            }
            
            // Send email to admin
            try {
                $adminEmail = env('MAIL_FROM_ADDRESS', 'admin@salengafarm.com');
                
                Mail::send('emails.role-request', [
                    'roleRequest' => $roleRequest,
                    'user' => $user
                ], function ($mail) use ($adminEmail, $user) {
                    $mail->to($adminEmail)
                         ->subject('New Client Role Request from ' . $user->first_name . ' ' . $user->last_name);
                });
                
                Log::info('Email sent successfully');
            } catch (\Exception $emailError) {
                Log::error('Failed to send email but request was saved: ' . $emailError->getMessage());
            }
            
            return redirect()->back()->with('success', 'Your client request has been submitted successfully! We will contact you soon.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed', ['errors' => $e->errors()]);
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Failed to submit client request: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', 'Failed to submit request: ' . $e->getMessage());
        }
    }
}
