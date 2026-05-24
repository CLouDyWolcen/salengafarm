<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Plant;
use App\Models\PlantRequest;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\PlantRequestMail;
use App\Services\BrevoEmailService;

class RequestFormController extends Controller
{
    /**
     * Display the request form page
     */
    public function index()
    {
        return view('user.request-form');
    }
    
    /**
     * Submit the request form
     */
    public function store(Request $request)
    {
        $requestId = $request->header('X-Request-ID', 'unknown');
        Log::info('Request form submission received', [
            'request_id' => $requestId,
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'plants_count' => count($request->input('plants', [])),
        ]);
        
        try {
            
            // Convert plant IDs to integers before validation
            $plantsData = $request->input('plants', []);
            foreach ($plantsData as $key => $plant) {
                if (isset($plant['id'])) {
                    $plantsData[$key]['id'] = (int)$plant['id'];
                }
            }
            $request->merge(['plants' => $plantsData]);
            
            // Validate the request
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'contact_number' => 'required|string|max:20',
                'plants' => 'required|array|min:1',
                'plants.*.id' => 'nullable|integer',
                'plants.*.name' => 'nullable|string',
                'plants.*.quantity' => 'required|integer|min:1',
                'plants.*.height' => 'nullable|numeric',
                'plants.*.spread' => 'nullable|numeric',
                'plants.*.spacing' => 'nullable|numeric',
            ], [
                'plants.required' => 'Please select at least one plant.',
                'plants.min' => 'Please select at least one plant.',
            ]);
            
            // Get plant details
            $plantData = [];
            foreach ($validated['plants'] as $index => $plant) {
                // Use the plant data as provided, don't look it up in database
                $plantData[] = [
                    'id' => $plant['id'] ?? null,
                    'name' => $plant['name'] ?? 'Unknown',
                    'code' => $plant['code'] ?? 'N/A',
                    'quantity' => $plant['quantity'],
                    'height' => $plant['height'] ?? null,
                    'spread' => $plant['spread'] ?? null,
                    'spacing' => $plant['spacing'] ?? null,
                ];
            }
            
            // Save the request to the database
            Log::info('Saving new plant request', ['user' => $validated['name'], 'email' => $validated['email']]);
            
            $plantRequest = new PlantRequest();
            $plantRequest->name = $validated['name'];
            $plantRequest->email = $validated['email'];
            $plantRequest->phone = $validated['contact_number'];
            $plantRequest->request_date = now();
            $plantRequest->due_date = now()->addDays(14);
            $plantRequest->items_json = $plantData;
            $plantRequest->status = 'pending';
            $plantRequest->request_type = 'user';
            $plantRequest->save();
            
            Log::info('Plant request saved successfully', ['id' => $plantRequest->id]);
            
            // Send email notification to the user using Brevo API
            try {
                Log::info('Attempting to send email via Brevo API', ['email' => $plantRequest->email]);
                
                $brevoService = new BrevoEmailService();
                $emailView = view('emails.plant-request', [
                    'request' => $plantRequest,
                    'recipientType' => 'User'
                ])->render();
                
                $result = $brevoService->sendEmail(
                    $plantRequest->email,
                    "Plant Request #{$plantRequest->id} - Quotation from Salenga Farm",
                    $emailView
                );
                
                if ($result['success']) {
                    Log::info('Email sent successfully via Brevo API', [
                        'email' => $plantRequest->email,
                        'messageId' => $result['messageId']
                    ]);
                } else {
                    Log::error('Brevo API returned error', [
                        'email' => $plantRequest->email,
                        'error' => $result['error']
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('Failed to send email via Brevo API', [
                    'email' => $plantRequest->email,
                    'error' => $e->getMessage(),
                    'error_class' => get_class($e),
                    'trace' => $e->getTraceAsString()
                ]);
                // Don't fail the request if email fails, just log it
            }
            
            // Create notification for admins and super admins about new plant request
            $admins = User::where('role', 'admin')->orWhere('role', 'super_admin')->get();
            foreach ($admins as $admin) {
                Notification::create([
                    'user_id' => $admin->id,
                    'type' => 'new_request',
                    'title' => 'New Plant Request',
                    'message' => "New plant request from {$validated['name']}",
                    'link' => '/requests',
                    'is_read' => false
                ]);
            }
            
            // Store the request data in session for the confirmation view
            session()->flash('request_data', [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'contact_number' => $validated['contact_number'],
                'plants' => $plantData,
                'created_at' => now()->format('Y-m-d H:i:s'),
            ]);
            
            // For AJAX requests, return JSON response
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Your plant request has been submitted successfully. A confirmation email has been sent to your email address.'
                ]);
            }
            
            // Redirect to confirmation page
            return redirect()->route('request-form.confirmation');
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed for plant request', [
                'errors' => $e->errors(),
                'input' => $request->all()
            ]);
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed: ' . implode(' ', array_map(fn($err) => implode(' ', $err), $e->errors()))
                ], 422);
            }
            
            return back()->withErrors($e->errors())->withInput();
            
        } catch (\Exception $e) {
            Log::error('Failed to save plant request', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'input' => $request->all()
            ]);
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to save your request. Please try again. Error: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Failed to save your request. Please try again.')->withInput();
        }
    }

    /**
     * Display the confirmation page
     */
    public function confirmation()
    {
        // Check if request data exists in session
        if (!session('request_data')) {
            return redirect()->route('home');
        }
        
        $requestData = session('request_data');
        
        return view('user.request-confirmation', [
            'requestData' => $requestData
        ]);
    }
} 