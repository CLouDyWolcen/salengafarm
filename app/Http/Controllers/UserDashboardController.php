<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Models\PlantRequest;
use App\Models\SiteVisitRequest;
use App\Models\SiteVisit;
use App\Models\Notification;
use App\Models\User;

class UserDashboardController extends Controller
{
    /**
     * Show the overview dashboard with stats (NEW)
     */
    public function dashboard()
    {
        $user = Auth::user();
        
        // Check if user has page access permission for dashboard
        if (!$user->hasAdminAccess() && !$user->hasPageAccess('dashboard')) {
            abort(403, 'Access denied. You do not have permission to access the Dashboard.');
        }

        // Get plant inquiries counts
        $inquiriesCount = PlantRequest::where('email', $user->email)->count();
        $inquiriesResponded = PlantRequest::where('email', $user->email)
            ->where('status', 'responded')->count();
        $inquiriesPending = PlantRequest::where('email', $user->email)
            ->where('status', 'pending')->count();

        // Get site visit requests counts
        $siteVisitRequestsCount = SiteVisitRequest::where('user_id', $user->id)->count();
        $siteVisitRequestsApproved = SiteVisitRequest::where('user_id', $user->id)
            ->where('status', 'approved')->count();
        $siteVisitRequestsPending = SiteVisitRequest::where('user_id', $user->id)
            ->where('status', 'pending')->count();

        // Get site visits counts (only those visible in Site Data page)
        $siteVisitsCount = SiteVisit::where('user_id', $user->id)
            ->where(function($q){
                $q->where('status', 'completed')
                  ->orWhere('client_data_open', true);
            })
            ->count();
        $siteVisitsActive = SiteVisit::where('user_id', $user->id)
            ->where(function($q){
                $q->where('status', 'completed')
                  ->orWhere('client_data_open', true);
            })
            ->whereIn('status', ['pending', 'follow_up'])->count();
        $siteVisitsCompleted = SiteVisit::where('user_id', $user->id)
            ->where('status', 'completed')->count();

        // Get recent activity
        $recentActivity = $this->getRecentActivity($user);

        // Get pending actions
        $pendingActions = $this->getPendingActions($user);

        return view('dashboard.overview', compact(
            'user',
            'inquiriesCount',
            'inquiriesResponded',
            'inquiriesPending',
            'siteVisitRequestsCount',
            'siteVisitRequestsApproved',
            'siteVisitRequestsPending',
            'siteVisitsCount',
            'siteVisitsActive',
            'siteVisitsCompleted',
            'recentActivity',
            'pendingActions'
        ));
    }

    /**
     * Get recent activity for the user
     */
    private function getRecentActivity($user)
    {
        $activities = collect();

        // Get recent plant inquiries
        $recentInquiries = PlantRequest::where('email', $user->email)
            ->orderByDesc('created_at')
            ->limit(3)
            ->get();

        foreach ($recentInquiries as $inquiry) {
            $activities->push([
                'type' => $inquiry->status === 'responded' ? 'success' : 'warning',
                'icon' => 'seedling',
                'title' => 'Plant Inquiry #' . $inquiry->id,
                'description' => $inquiry->status === 'responded' 
                    ? 'Your inquiry received a response' 
                    : 'Inquiry submitted and pending review',
                'date' => $inquiry->created_at->diffForHumans(),
                'link' => $inquiry->status === 'responded' 
                    ? route('user.inquiry.response', $inquiry->id) 
                    : null,
            ]);
        }

        // Get recent site visit requests
        $recentRequests = SiteVisitRequest::where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->limit(3)
            ->get();

        foreach ($recentRequests as $request) {
            $type = match($request->status) {
                'approved' => 'success',
                'rejected' => 'danger',
                default => 'warning',
            };

            $activities->push([
                'type' => $type,
                'icon' => 'calendar-check',
                'title' => 'Site Visit Request',
                'description' => match($request->status) {
                    'approved' => 'Your request was approved',
                    'rejected' => 'Your request was declined',
                    default => 'Request submitted and pending review',
                },
                'date' => $request->created_at->diffForHumans(),
                'link' => route('client-data.index'),
            ]);
        }

        // Get recent site visits
        $recentVisits = SiteVisit::where('user_id', $user->id)
            ->orderByDesc('visit_date')
            ->limit(2)
            ->get();

        foreach ($recentVisits as $visit) {
            $activities->push([
                'type' => $visit->status === 'completed' ? 'success' : 'info',
                'icon' => 'map-marked-alt',
                'title' => 'Site Visit Scheduled',
                'description' => 'Visit scheduled for ' . $visit->visit_date->format('M d, Y'),
                'date' => $visit->created_at->diffForHumans(),
                'link' => route('client-data.show', $visit->id),
            ]);
        }

        // Sort by date and limit to 5
        return $activities->sortByDesc(function($activity) {
            return strtotime($activity['date']);
        })->take(5)->values();
    }

    /**
     * Get pending actions for the user
     */
    private function getPendingActions($user)
    {
        $actions = collect();

        // Check for pending site visit requests
        $pendingRequests = SiteVisitRequest::where('user_id', $user->id)
            ->where('status', 'pending')
            ->get();

        foreach ($pendingRequests as $request) {
            $actions->push([
                'icon' => 'clock',
                'title' => 'Site Visit Request Pending',
                'description' => 'Your request for ' . $request->property_address . ' is awaiting approval',
                'date' => 'Submitted ' . $request->created_at->diffForHumans(),
                'link' => route('client-data.index'),
                'action' => 'View Request',
            ]);
        }

        // Check for site visits with open uploads
        $openUploads = SiteVisit::where('user_id', $user->id)
            ->where('client_data_open', true)
            ->get();

        foreach ($openUploads as $visit) {
            $actions->push([
                'icon' => 'upload',
                'title' => 'Upload Required',
                'description' => 'Please upload documents for site visit at ' . $visit->location,
                'date' => 'Due soon',
                'link' => route('client-data.show', $visit->id),
                'action' => 'Upload Now',
            ]);
        }

        return $actions;
    }

    /**
     * Show plant inquiries list (RENAMED from index)
     */
    public function inquiries()
    {
        $user = Auth::user();
        
        // Check if user has page access permission for dashboard
        if (!$user->hasAdminAccess() && !$user->hasPageAccess('dashboard')) {
            abort(403, 'Access denied. You do not have permission to access My Requests.');
        }

        // Tab 1: Plant Inquiries (simple questions - request_type = 'user')
        $plantInquiries = PlantRequest::where('email', $user->email)
            ->where('request_type', 'user')
            ->orderByDesc('created_at')
            ->get();

        // Tab 2: RFQ Requests (formal quotations - request_type = 'client' or null)
        $rfqRequests = PlantRequest::where('email', $user->email)
            ->where(function($q) {
                $q->where('request_type', 'client')
                  ->orWhereNull('request_type'); // Legacy records
            })
            ->orderByDesc('created_at')
            ->get();

        // Tab 3: Site Visit Requests
        $siteVisitRequests = SiteVisitRequest::where('user_id', $user->id)
            ->with(['reviewer'])
            ->orderByDesc('created_at')
            ->get();

        return view('requests.index', [
            'user' => $user,
            'plantInquiries' => $plantInquiries,
            'rfqRequests' => $rfqRequests,
            'siteVisitRequests' => $siteVisitRequests,
        ]);
    }
    
    /**
     * Show the user/client dashboard (OLD - kept for backward compatibility)
     */
    public function index(Request $request)
    {
        // Redirect to new dashboard
        return redirect()->route('dashboard.user');
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
