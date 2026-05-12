<?php

namespace App\Http\Controllers;

use App\Models\PlantRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\PlantRequestMail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Notification;
use App\Models\User;
use App\Services\BrevoEmailService;

class ClientRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get client requests (default type or 'client' type)
        $clientRequests = PlantRequest::where(function($query) {
                $query->whereNull('request_type')
                      ->orWhere('request_type', 'client');
            })
            ->orderBy('request_date', 'desc')
            ->get();
            
        // Get user requests (simple inquiries)
        $userRequests = PlantRequest::where('request_type', 'user')
            ->orderBy('request_date', 'desc')
            ->get();
            
        return view('admin.requests.index', compact('clientRequests', 'userRequests'));
    }
    
    public function sendEmail($id)
    {
        $request = PlantRequest::findOrFail($id);
        
        // Add debugging
        Log::info('SendEmail method called', [
            'request_id' => $id,
            'is_ajax' => request()->ajax(),
            'expects_json' => request()->expectsJson(),
            'headers' => request()->headers->all()
        ]);
        
        // Determine which tab to return to based on request_type
        $activeTab = ($request->request_type === 'user') ? 'user-requests' : 'client-requests';
        
        try {
            // Generate PDF if it doesn't exist
            if (!$request->pdf_path || !Storage::exists($request->pdf_path)) {
                $this->generatePdf($id);
                $request->refresh(); // Refresh to get the updated pdf_path
            }
            
            // Determine recipient type for email subject and content
            $recipientType = ($request->request_type == 'user') ? 'User' : 'Client';
            $subject = "Plant Request #{$request->id} - Quotation from Salenga Farm";
            
            // Attempt to send email using Laravel Mail (Gmail SMTP)
            $emailSent = false;
            $errorMessage = '';
            
            try {
                Log::info('Attempting to send email via Gmail SMTP', [
                    'request_id' => $request->id,
                    'recipient' => $request->email,
                    'type' => $recipientType
                ]);
                
                // Attach PDF only for client requests (not user requests)
                $attachmentPath = null;
                if ($request->request_type === 'client' && $request->pdf_path) {
                    $attachmentPath = $request->pdf_path;
                }
                
                // Send email using Laravel Mail
                Mail::send('emails.plant-request', [
                    'request' => $request,
                    'recipientType' => $recipientType
                ], function ($message) use ($request, $subject, $attachmentPath) {
                    $message->to($request->email)
                            ->subject($subject);
                    
                    // Attach PDF for client requests
                    if ($attachmentPath && Storage::exists($attachmentPath)) {
                        $message->attach(Storage::path($attachmentPath));
                    }
                });
                
                $emailSent = true;
                Log::info('Gmail SMTP email sent successfully', [
                    'request_id' => $request->id,
                    'recipient' => $request->email,
                    'has_pdf' => !empty($attachmentPath)
                ]);
                
            } catch (\Exception $mailException) {
                $emailSent = false;
                $errorMessage = $mailException->getMessage();
                
                Log::error('Gmail SMTP email sending failed', [
                    'error' => $mailException->getMessage(),
                    'trace' => $mailException->getTraceAsString(),
                    'request_id' => $request->id,
                    'recipient' => $request->email
                ]);
            }
            
            // Provide appropriate feedback based on email sending result
            if ($emailSent) {
                // Only update status if email was actually sent
                $request->status = 'sent';
                $request->save();
                
                // Create notification for the user/client if they have an account
                $user = User::where('email', $request->email)->first();
                if ($user) {
                    Notification::create([
                        'user_id' => $user->id,
                        'type' => 'request_sent',
                        'title' => 'Request Sent',
                        'message' => "Your plant request has been processed and sent to your email",
                        'link' => '/dashboard/user',
                        'is_read' => false
                    ]);
                }
                
                // Check if this is an AJAX request
                if (request()->expectsJson() || request()->ajax()) {
                    Log::info('Returning JSON response for successful email');
                    return response()->json([
                        'success' => true,
                        'message' => "Email sent successfully to {$recipientType} ({$request->email})!",
                        'activeTab' => $activeTab
                    ]);
                }
                
                Log::info('Returning redirect response for successful email');
                return redirect()->route('requests.index')->with('success', "Email sent successfully to {$recipientType} ({$request->email})!")->with('activeTab', $activeTab);
            } else {
                // Email failed, provide detailed error message
                $errorDetails = '';
                
                // Check for common Gmail authentication errors
                if (strpos($errorMessage, 'Username and Password not accepted') !== false) {
                    $errorDetails = ' Please check your Gmail App Password configuration. You may need to generate a new App Password from your Google Account settings.';
                } elseif (strpos($errorMessage, 'authentication') !== false) {
                    $errorDetails = ' Please verify your email configuration and credentials.';
                }
                
                $errorMessage = "Failed to send email to {$recipientType} ({$request->email}).{$errorDetails}";
                
                // Check if this is an AJAX request
                if (request()->expectsJson() || request()->ajax()) {
                    Log::info('Returning JSON error response');
                    return response()->json([
                        'success' => false,
                        'message' => $errorMessage,
                        'activeTab' => $activeTab
                    ], 422);
                }
                
                Log::info('Returning redirect error response');
                return redirect()->route('requests.index')->with('error', $errorMessage)->with('activeTab', $activeTab);
            }
            
        } catch (\Exception $e) {
            Log::error('Failed to process email request: ' . $e->getMessage(), [
                'request_id' => $request->id,
                'trace' => $e->getTraceAsString()
            ]);
            
            $errorMessage = 'Failed to process email request. Please try again.';
            
            // Check if this is an AJAX request
            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage,
                    'activeTab' => $activeTab
                ], 500);
            }
            
            return redirect()->route('requests.index')->with('error', $errorMessage)->with('activeTab', $activeTab);
        }
    }

    public function store(Request $request)
    {
        try {
            Log::info('Received plant request: ' . json_encode($request->all()));
            
            // Detect if request is JSON
            if ($request->isJson() || $request->header('Content-Type') === 'application/json') {
                // Handle JSON request
                $validator = Validator::make($request->all(), [
                    'email' => 'required|email|max:255',
                    'name' => 'nullable|string|max:255',
                    'items_json' => 'required|string',
                    'pricing' => 'nullable|string|in:None,Low cost,High cost',
                ]);
                
                if ($validator->fails()) {
                    return response()->json([
                        'message' => $validator->errors()->first()
                    ], 422);
                }
                
                // Create new plant request
                $plantRequest = new PlantRequest();
                $plantRequest->email = $request->email;
                // Use authenticated user's name if available, otherwise use provided name or 'Guest User'
                if (auth()->check()) {
                    $user = auth()->user();
                    $plantRequest->name = $user->first_name && $user->last_name 
                        ? $user->first_name . ' ' . $user->last_name 
                        : ($user->name ?? $request->name ?? 'Guest User');
                } else {
                    $plantRequest->name = $request->name ?? 'Guest User';
                }
                $plantRequest->request_date = now();
                $plantRequest->due_date = now()->addDays(14);
                
                // Parse items_json, which might be a string that needs to be decoded
                $itemsJson = $request->items_json;
                
                // Log for debugging
                Log::info('Items JSON type: ' . gettype($itemsJson));
                Log::info('Items JSON value: ' . print_r($itemsJson, true));
                
                if (is_string($itemsJson)) {
                    $decoded = json_decode($itemsJson, true);
                    Log::info('Decoded items: ' . print_r($decoded, true));
                    $plantRequest->items_json = $decoded;
                } else {
                    Log::info('Items already an array');
                    $plantRequest->items_json = $itemsJson;
                }
                
                // Log final items_json
                Log::info('Final items_json to save: ' . print_r($plantRequest->items_json, true));
                
                // Set pricing field (default to None if not provided)
                $plantRequest->pricing = $request->pricing ?? 'None';
                
                $plantRequest->status = 'pending';
                $plantRequest->request_type = 'client';
                $plantRequest->save();
                
                // Generate PDF
                $this->generatePdf($plantRequest->id);
                
                // Create notification for admins
                $admins = User::where('role', 'admin')->get();
                foreach ($admins as $admin) {
                    Notification::create([
                        'user_id' => $admin->id,
                        'type' => 'new_request',
                        'title' => 'New Plant Request',
                        'message' => "New plant request from {$plantRequest->name}",
                        'link' => '/requests',
                        'is_read' => false
                    ]);
                }
                
                return response()->json([
                    'message' => 'Request submitted successfully!',
                    'request_id' => $plantRequest->id
                ]);
            } else {
                // Handle form submission
                $validator = Validator::make($request->all(), [
                    'email' => 'required|email|max:255',
                    'name' => 'nullable|string|max:255',
                    'items_json' => 'required|string',
                    'pricing' => 'nullable|string|in:None,Low cost,High cost',
                ]);
                
                if ($validator->fails()) {
                    return back()->withErrors($validator)->withInput();
                }
                
                // Create new plant request
                $plantRequest = new PlantRequest();
                $plantRequest->email = $request->email;
                // Use authenticated user's name if available, otherwise use provided name or 'Guest User'
                if (auth()->check()) {
                    $user = auth()->user();
                    $plantRequest->name = $user->first_name && $user->last_name 
                        ? $user->first_name . ' ' . $user->last_name 
                        : ($user->name ?? $request->name ?? 'Guest User');
                } else {
                    $plantRequest->name = $request->name ?? 'Guest User';
                }
                $plantRequest->request_date = now();
                $plantRequest->due_date = now()->addDays(14);
                $plantRequest->items_json = json_decode($request->items_json, true);
                $plantRequest->pricing = $request->pricing ?? 'None';
                $plantRequest->status = 'pending';
                $plantRequest->request_type = 'client';
                $plantRequest->save();
                
                // Generate PDF
                $this->generatePdf($plantRequest->id);
                
                // Create notification for admins
                $admins = User::where('role', 'admin')->get();
                foreach ($admins as $admin) {
                    Notification::create([
                        'user_id' => $admin->id,
                        'type' => 'new_request',
                        'title' => 'New Plant Request',
                        'message' => "New plant request from {$plantRequest->name}",
                        'link' => '/requests',
                        'is_read' => false
                    ]);
                }
                
                return redirect()->route('requests.index')->with('success', 'Request submitted successfully!');
            }
        } catch (\Exception $e) {
            Log::error('Failed to process plant request: ' . $e->getMessage());
            
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'An error occurred while processing your request. Please try again.',
                    'error' => $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'An error occurred while processing your request. Please try again.');
        }
    }
    
    
    public function generatePdf($id)
    {
        try {
            $request = PlantRequest::findOrFail($id);
            
            // Process items_json to ensure data is formatted correctly without defaults
            $items = [];
            foreach ($request->items_json as $item) {
                // Base item with minimal fields - no defaults for quantity or prices
                $processedItem = [
                    'name' => $item['name'] ?? '',
                    'code' => isset($item['code']) && $item['code'] != 'N/A' ? $item['code'] : '',
                    'height' => isset($item['height']) && $item['height'] != '' ? $item['height'] : '',
                    'spread' => isset($item['spread']) && $item['spread'] != '' ? $item['spread'] : '',
                    'spacing' => isset($item['spacing']) && $item['spacing'] != '' ? $item['spacing'] : '',
                    'remarks' => isset($item['remarks']) ? $item['remarks'] : '',
                    'availability' => isset($item['availability']) ? $item['availability'] : ''
                ];
                
                // Only include quantity if explicitly set
                if (isset($item['quantity']) && $item['quantity'] !== '') {
                    $processedItem['quantity'] = $item['quantity'];
                }
                
                // Only include unit_price if explicitly set - pass through original value
                if (isset($item['unit_price']) && $item['unit_price'] !== '' && $item['unit_price'] != 0) {
                    $processedItem['unit_price'] = $item['unit_price'];
                }
                
                // Only include total_price if explicitly set - pass through original value
                if (isset($item['total_price']) && $item['total_price'] !== '' && $item['total_price'] != 0) {
                    $processedItem['total_price'] = $item['total_price'];
                }
                
                $items[] = $processedItem;
            }
            
            // Update request items with clean data
            $request->items_json = $items;
            
            // Create PDF with ultra-compact settings
            $options = [
                'margin-top' => '0.4cm',
                'margin-right' => '0.4cm',
                'margin-bottom' => '0.4cm',
                'margin-left' => '0.4cm',
                'page-size' => 'A4',
                'orientation' => 'portrait',
                'encoding' => 'UTF-8',
                'font-family' => 'dejavusans',
                'font-size' => '9',
                'image-dpi' => '300',
                'enable-local-file-access' => true,
                'disable-smart-shrinking' => true,
                'default-encoding' => 'UTF-8',
                'charset-input' => 'UTF-8',
                'lowquality' => false,
                'enable-internal-links' => true, 
                'enable-external-links' => true,
                'print-media-type' => true,
                'no-outline' => true
            ];
            
            // Choose the correct PDF template based on request type
            $pdfView = $request->request_type === 'user' ? 'pdf.user-request' : 'pdf.rfq';
            
            // Generate PDF with proper font that supports Unicode
            $pdf = PDF::loadView($pdfView, compact('request'))
                      ->setOption('enable-javascript', false)
                      ->setOption('javascript-delay', '0')
                      ->setOption('enable-smart-shrinking', false)
                      ->setOption('no-stop-slow-scripts', true)
                      ->setOptions($options)
                      ->setPaper('a4', 'portrait');
            
            // Save PDF to storage
            $filename = 'rfq_' . $id . '_' . time() . '.pdf';
            $path = 'pdfs/' . $filename;
            
            Storage::put($path, $pdf->output());
            
            // Update request with PDF path
            $request->pdf_path = $path;
            $request->save();
            
            return $path;
        } catch (\Exception $e) {
            Log::error('Failed to generate PDF: ' . $e->getMessage());
            throw $e;
        }
    }
    
    public function downloadPdf($id)
    {
        try {
            $request = PlantRequest::findOrFail($id);
            
            // Authorization: Allow if user is admin/manager/super_admin OR if it's their own request
            $user = auth()->user();
            $isAdminOrManager = in_array($user->role, ['admin', 'manager', 'super_admin']);
            
            if (!$isAdminOrManager && $request->email !== $user->email) {
                abort(403, 'Unauthorized access to this PDF.');
            }
            
            // Generate PDF if it doesn't exist
            if (!$request->pdf_path || !Storage::exists($request->pdf_path)) {
                $this->generatePdf($id);
                $request->refresh(); // Refresh to get the updated pdf_path
            }
            
            // Return the PDF for download
            return Storage::download($request->pdf_path, 'RFQ_' . $id . '.pdf');
        } catch (\Exception $e) {
            Log::error('Failed to download PDF: ' . $e->getMessage());
            
            return redirect()->back()->with('error', 'Failed to download PDF. Please try again.');
        }
    }
    
    public function viewPdf($id)
    {
        try {
            $request = PlantRequest::findOrFail($id);
            
            // Authorization: Allow if user is admin/manager/super_admin OR if it's their own request
            $user = auth()->user();
            $isAdminOrManager = in_array($user->role, ['admin', 'manager', 'super_admin']);
            
            if (!$isAdminOrManager && $request->email !== $user->email) {
                abort(403, 'Unauthorized access to this PDF.');
            }
            
            // Generate PDF if it doesn't exist
            if (!$request->pdf_path || !Storage::exists($request->pdf_path)) {
                $this->generatePdf($id);
                $request->refresh(); // Refresh to get the updated pdf_path
            }
            
            // Return the PDF for viewing
            return response()->file(Storage::path($request->pdf_path));
        } catch (\Exception $e) {
            Log::error('Failed to view PDF: ' . $e->getMessage());
            
            return redirect()->back()->with('error', 'Failed to view PDF. Please try again.');
        }
    }

    public function destroy($id)
    {
        try {
            $request = PlantRequest::findOrFail($id);
            
            // Authorization: Allow if user is admin/manager/super_admin OR if it's their own request
            $user = auth()->user();
            $isAdminOrManager = in_array($user->role, ['admin', 'manager', 'super_admin']);
            
            // More flexible email matching (case insensitive)
            $userEmail = strtolower(trim($user->email));
            $requestEmail = strtolower(trim($request->email));
            $isOwnRequest = $userEmail === $requestEmail;
            
            Log::info('Delete request authorization check', [
                'user_id' => $user->id,
                'user_email' => $userEmail,
                'user_role' => $user->role,
                'request_id' => $request->id,
                'request_email' => $requestEmail,
                'is_admin_or_manager' => $isAdminOrManager,
                'is_own_request' => $isOwnRequest
            ]);
            
            if (!$isAdminOrManager && !$isOwnRequest) {
                Log::warning('Unauthorized delete attempt', [
                    'user_id' => $user->id,
                    'user_email' => $userEmail,
                    'request_id' => $request->id,
                    'request_email' => $requestEmail
                ]);
                abort(403, 'Unauthorized to delete this request.');
            }
            
            // Determine which tab to return to based on request_type
            $activeTab = ($request->request_type === 'user') ? 'user-requests' : 'client-requests';
            
            // Delete the associated PDF file if it exists
            if ($request->pdf_path && Storage::exists($request->pdf_path)) {
                Storage::delete($request->pdf_path);
            }
            
            $request->delete();
            
            Log::info('Request deleted successfully', [
                'request_id' => $request->id,
                'deleted_by' => $user->id
            ]);
            
            // Redirect based on user role
            if ($isAdminOrManager) {
                return redirect()->route('requests.index')->with('success', 'Request deleted successfully.')->with('activeTab', $activeTab);
            } else {
                return redirect()->route('dashboard.user')->with('success', 'Inquiry deleted successfully.');
            }
        } catch (\Exception $e) {
            Log::error('Failed to delete request: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to delete request. Please try again.');
        }
    }

    public function updateRequest($id, Request $request)
    {
        try {
            $plantRequest = PlantRequest::findOrFail($id);
            
            // Validate the items_json
            $validator = Validator::make($request->all(), [
                'items_json' => 'required|string',
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'message' => $validator->errors()->first()
                ], 422);
            }
            
            // Parse and prepare items
            $itemsData = json_decode($request->items_json, true);
            $processedItems = [];
            
            foreach ($itemsData as $item) {
                // Base item with minimal fields - no defaults
                $processedItem = [
                    'name' => $item['name'] ?? '',
                    'code' => $item['code'] ?? '',
                    'height' => $item['height'] ?? '',
                    'spread' => $item['spread'] ?? '',
                    'spacing' => $item['spacing'] ?? '',
                    'remarks' => $item['remarks'] ?? '',
                    'availability' => $item['availability'] ?? ''
                ];
                
                // Only include quantity if explicitly set
                if (isset($item['quantity']) && $item['quantity'] !== '') {
                    $processedItem['quantity'] = $item['quantity'];
                }
                
                // Only include unit_price if explicitly set - pass through original value
                if (isset($item['unit_price']) && $item['unit_price'] !== '' && $item['unit_price'] != 0) {
                    $processedItem['unit_price'] = $item['unit_price'];
                }
                
                // Only include total_price if explicitly set - pass through original value
                if (isset($item['total_price']) && $item['total_price'] !== '' && $item['total_price'] != 0) {
                    $processedItem['total_price'] = $item['total_price'];
                }
                
                $processedItems[] = $processedItem;
            }
            
            // Update the items_json field with properly processed items
            $plantRequest->items_json = $processedItems;
            $plantRequest->save();
            
            // Regenerate PDF with updated information
            $this->generatePdf($id);
            
            return response()->json([
                'message' => 'Request updated successfully!'
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to update request: ' . $e->getMessage());
            
            return response()->json([
                'message' => 'Failed to update request. Please try again.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function plainViewRequest($id)
    {
        try {
            // Find the request by ID
            $request = PlantRequest::findOrFail($id);
            
            // Log for debugging
            Log::info('Plain HTML view request', [
                'id' => $id,
                'items_count' => count($request->items_json),
                'controller' => 'ClientRequestController@plainViewRequest'
            ]);
            
            // Create a local copy of the array
            $items = is_array($request->items_json) ? $request->items_json : [];
            
            // Process each item to ensure all needed fields exist
            foreach ($items as &$item) {
                // Set default values for any missing fields
                $item['quantity'] = $item['quantity'] ?? 1;
                $item['code'] = $item['code'] ?? '';
                $item['height'] = $item['height'] ?? '';
                $item['spread'] = $item['spread'] ?? '';
                $item['spacing'] = $item['spacing'] ?? '';
                $item['remarks'] = $item['remarks'] ?? '';
                $item['unit_price'] = $item['unit_price'] ?? '';
                $item['total_price'] = $item['total_price'] ?? '';
                $item['availability'] = $item['availability'] ?? '';
            }
            
            // Return the Blade view with data
            return view('requests.view-request', compact('request', 'items'));
            
        } catch (\Exception $e) {
            // Log any errors
            Log::error('Failed to plain view request', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'id' => $id
            ]);
            
            // Return a simple error page
            return response('Error viewing request: ' . $e->getMessage(), 500)
                ->header('Content-Type', 'text/html');
        }
    }


    public function showSuccess($id)
    {
        $request = PlantRequest::findOrFail($id);
        return view('user.request-success', compact('request'));
    }

    public function updatePricing($id, Request $request)
    {
        try {
            $plantRequest = PlantRequest::findOrFail($id);
            
            // Validate the pricing
            $validator = Validator::make($request->all(), [
                'pricing' => 'required|string|in:None,Low cost,High cost',
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'message' => $validator->errors()->first()
                ], 422);
            }
            
            // Update the pricing field
            $plantRequest->pricing = $request->pricing;
            $plantRequest->save();
            
            return response()->json([
                'message' => 'Pricing updated successfully!'
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to update pricing: ' . $e->getMessage());
            
            return response()->json([
                'message' => 'Failed to update pricing. Please try again.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updateInfo($id, Request $request)
    {
        try {
            $plantRequest = PlantRequest::findOrFail($id);
            
            // Validate the request
            $validator = Validator::make($request->all(), [
                'status' => 'required|string|in:pending,responded,cancelled',
                'request_date' => 'required|date',
                'due_date' => 'nullable|date|after_or_equal:request_date',
            ]);
            
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            
            // Update the fields
            $plantRequest->status = $request->status;
            $plantRequest->request_date = $request->request_date;
            $plantRequest->due_date = $request->due_date;
            $plantRequest->save();
            
            // Regenerate PDF with updated information
            $this->generatePdf($id);
            
            return redirect()->back()->with('success', 'Request information updated successfully!');
        } catch (\Exception $e) {
            Log::error('Failed to update request information: ' . $e->getMessage());
            
            return redirect()->back()->with('error', 'Failed to update request information. Please try again.');
        }
    }
    
    public function updateClient($id, Request $request)
    {
        try {
            $plantRequest = PlantRequest::findOrFail($id);
            
            // Validate the request
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
            ]);
            
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            
            // Update the fields
            $plantRequest->name = $request->name;
            $plantRequest->email = $request->email;
            $plantRequest->save();
            
            // Regenerate PDF with updated information
            $this->generatePdf($id);
            
            $infoType = ($plantRequest->request_type == 'user') ? 'User' : 'Client';
            return redirect()->back()->with('success', $infoType . ' information updated successfully!');
        } catch (\Exception $e) {
            Log::error('Failed to update client information: ' . $e->getMessage());
            
            return redirect()->back()->with('error', 'Failed to update information. Please try again.');
        }
    }
    
    public function updateItems($id, Request $request)
    {
        try {
            $plantRequest = PlantRequest::findOrFail($id);
            
            // Get the items array directly from the form data
            $itemsData = $request->input('items', []);
            
            if (empty($itemsData)) {
                return redirect()->back()->with('error', 'No items data provided.');
            }
            
            // Process each item to ensure all needed fields exist
            $processedItems = [];
            foreach ($itemsData as $item) {
                // Base item with required fields
                $processedItem = [
                    'name' => $item['name'] ?? '',
                    'quantity' => $item['quantity'] ?? 1,
                ];
                
                // Optional fields
                if (isset($item['code']) && $item['code'] !== '') $processedItem['code'] = $item['code'];
                if (isset($item['height']) && $item['height'] !== '') $processedItem['height'] = $item['height'];
                if (isset($item['spread']) && $item['spread'] !== '') $processedItem['spread'] = $item['spread'];
                if (isset($item['spacing']) && $item['spacing'] !== '') $processedItem['spacing'] = $item['spacing'];
                if (isset($item['remarks']) && $item['remarks'] !== '') $processedItem['remarks'] = $item['remarks'];
                
                // Availability field (for user requests)
                if (isset($item['availability']) && $item['availability'] !== '') {
                    $processedItem['availability'] = $item['availability'];
                }
                
                // Price fields (for client requests)
                if (isset($item['unit_price']) && is_numeric($item['unit_price']) && $item['unit_price'] > 0) {
                    $processedItem['unit_price'] = floatval($item['unit_price']);
                }
                
                if (isset($item['total_price']) && is_numeric($item['total_price']) && $item['total_price'] > 0) {
                    $processedItem['total_price'] = floatval($item['total_price']);
                }
                
                $processedItems[] = $processedItem;
            }
            
            // Save updated items
            $plantRequest->items_json = $processedItems;
            $plantRequest->save();
            
            // Regenerate PDF with updated information
            $this->generatePdf($id);
            
            return redirect()->back()->with('success', 'Items updated successfully!');
        } catch (\Exception $e) {
            Log::error('Failed to update items: ' . $e->getMessage());
            
            return redirect()->back()->with('error', 'Failed to update items. Please try again.');
        }
    }
    
    /**
     * Send response to user for their inquiry
     * 
     * @param int $id - Plant request ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendResponse($id)
    {
        try {
            $request = PlantRequest::findOrFail($id);
            
            // Validate that items have availability set
            $items = is_array($request->items_json) ? $request->items_json : json_decode($request->items_json, true);
            $hasAvailability = false;
            foreach ($items as $item) {
                if (!empty($item['availability'])) {
                    $hasAvailability = true;
                    break;
                }
            }
            
            if (!$hasAvailability) {
                return redirect()->back()->with('error', 'Please set availability for at least one plant before sending response.');
            }
            
            // Update request status
            $request->status = 'responded';
            $request->response_sent_at = now();
            $request->responded_by = auth()->id();
            $request->save();
            
            // Send email notification to user
            try {
                $subject = "Response to Your Plant Inquiry #{$request->id} - Salenga Farm";
                
                Mail::send('emails.inquiry-response', [
                    'request' => $request,
                    'viewLink' => url('/dashboard/user')
                ], function ($message) use ($request, $subject) {
                    $message->to($request->email)
                            ->subject($subject);
                });
                
                Log::info('Response email sent successfully', [
                    'request_id' => $request->id,
                    'recipient' => $request->email
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to send response email', [
                    'request_id' => $request->id,
                    'error' => $e->getMessage()
                ]);
            }
            
            // Create in-app notification for user
            $user = User::where('email', $request->email)->first();
            if ($user) {
                Notification::create([
                    'user_id' => $user->id,
                    'type' => 'inquiry_response',
                    'title' => 'Inquiry Response',
                    'message' => "Your inquiry #{$request->id} has been responded to. Click to view details.",
                    'link' => '/user/inquiries/' . $request->id . '/response',
                    'is_read' => false
                ]);
            }
            
            return redirect()->back()->with('success', 'Response sent successfully to user dashboard.');
            
        } catch (\Exception $e) {
            Log::error('Failed to send response: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to send response: ' . $e->getMessage());
        }
    }
    
    /**
     * Send response email to user using Brevo API
     * 
     * @param PlantRequest $request
     * @return void
     */
    private function sendResponseEmail($request)
    {
        $brevoService = new BrevoEmailService();
        
        $subject = "Response to Your Plant Inquiry #{$request->id}";
        
        $htmlContent = view('emails.inquiry-response', [
            'request' => $request,
            'viewLink' => route('user.inquiry.response', $request->id)
        ])->render();
        
        $brevoService->sendEmail(
            $request->email,
            $request->name,
            $subject,
            $htmlContent
        );
    }
}