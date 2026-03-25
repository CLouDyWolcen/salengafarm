# Appendix 11. Relevant Source Code

This appendix contains key source code snippets that demonstrate the core functionality of the Comprehensive Plant Inventory and Site Visit Management System for Salenga Farm.

## 11.1 User Role Management System

**File: `app/Models/User.php`**

```php
<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $fillable = [
        'name', 'first_name', 'last_name', 'contact_number',
        'company_name', 'company_address', 'address', 'gender',
        'account_type', 'email', 'password', 'role', 'avatar', 'is_client',
    ];

    /**
     * Check if the user is an admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if the user is a super admin.
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    /**
     * Check if the user has access to admin features.
     */
    public function hasAdminAccess(): bool
    {
        return in_array($this->role, ['super_admin', 'admin']);
    }

    /**
     * Check if the user is a client.
     */
    public function isClient(): bool
    {
        return (bool)$this->is_client;
    }

    /**
     * Check if the user has access to client features.
     */
    public function hasClientAccess(): bool
    {
        return (bool)$this->is_client || in_array($this->role, ['super_admin', 'admin']);
    }
}
```

## 11.2 Plant Inventory Management

**File: `app/Http/Controllers/PlantController.php`**

```php
<?php

namespace App\Http\Controllers;

use App\Models\Plant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PlantController extends Controller
{
    /**
     * Display plant inventory with categories
     */
    public function index()
    {
        $plants = Plant::orderBy('name', 'asc')->get();
        $categories = \App\Models\Category::all();
        return view('plants.index', compact('plants', 'categories'));
    }

    /**
     * Store a new plant in inventory
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'scientific_name' => 'nullable|string|max:255',
            'category' => 'required|string',
            'description' => 'nullable|string',
            'height_mm' => 'nullable|numeric',
            'spread_mm' => 'nullable|numeric',
            'spacing_mm' => 'nullable|numeric',
            'photo' => 'nullable|image|max:2048',
            'price' => 'nullable|numeric|min:0',
            'quantity' => 'nullable|numeric|min:0'
        ]);

        $plant = new Plant($validated);

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('plant-photos', 'public');
            $plant->photo_path = $path;
        }

        $plant->save();

        return response()->json([
            'success' => true,
            'message' => 'Plant added successfully!',
            'plant' => $plant
        ]);
    }

    /**
     * Update plant information
     */
    public function update(Request $request, Plant $plant)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string',
            'price' => 'nullable|numeric|min:0',
            'quantity' => 'nullable|numeric|min:0'
        ]);

        $plant->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Plant updated successfully!'
        ]);
    }
}
```

## 11.3 Request Management System

**File: `app/Http/Controllers/ClientRequestController.php`**

```php
<?php

namespace App\Http\Controllers;

use App\Models\PlantRequest;
use Illuminate\Http\Request;
use App\Services\BrevoEmailService;
use Barryvdh\DomPDF\Facade\Pdf;

class ClientRequestController extends Controller
{
    /**
     * Display client and user requests
     */
    public function index()
    {
        // Get client RFQ requests
        $clientRequests = PlantRequest::where(function($query) {
                $query->whereNull('request_type')
                      ->orWhere('request_type', 'client');
            })
            ->orderBy('request_date', 'desc')
            ->get();
            
        // Get user plant inquiries
        $userRequests = PlantRequest::where('request_type', 'user')
            ->orderBy('request_date', 'desc')
            ->get();
            
        return view('admin.requests.index', compact('clientRequests', 'userRequests'));
    }

    /**
     * Send email with quotation to client
     */
    public function sendEmail($id)
    {
        $request = PlantRequest::findOrFail($id);
        
        try {
            // Generate PDF if it doesn't exist
            if (!$request->pdf_path) {
                $this->generatePdf($id);
                $request->refresh();
            }
            
            $recipientType = ($request->request_type == 'user') ? 'User' : 'Client';
            $subject = "Plant Request #{$request->id} - Quotation from Salenga Farm";
            
            // Send email using Brevo API
            $brevoService = new BrevoEmailService();
            $emailView = view('emails.plant-request', [
                'request' => $request,
                'recipientType' => $recipientType
            ])->render();
            
            // Attach PDF for client requests only
            $attachmentPath = ($request->request_type === 'client') ? $request->pdf_path : null;
            
            $result = $brevoService->sendEmail(
                $request->email,
                $subject,
                $emailView,
                null,
                null,
                $attachmentPath
            );
            
            if ($result['success']) {
                $request->update(['status' => 'sent']);
                return back()->with('success', 'Email sent successfully!');
            } else {
                return back()->with('error', 'Failed to send email: ' . $result['error']);
            }
            
        } catch (\Exception $e) {
            return back()->with('error', 'Error sending email: ' . $e->getMessage());
        }
    }

    /**
     * Generate PDF quotation
     */
    public function generatePdf($id)
    {
        $request = PlantRequest::findOrFail($id);
        
        $pdf = Pdf::loadView('pdf.rfq', compact('request'));
        $filename = "rfq_{$request->id}_" . time() . '.pdf';
        $path = "pdfs/{$filename}";
        
        Storage::put($path, $pdf->output());
        
        $request->update(['pdf_path' => $path]);
        
        return $pdf;
    }
}
```

## 11.4 Email Service Integration

**File: `app/Services/BrevoEmailService.php`**

```php
<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class BrevoEmailService
{
    private $apiKey;
    private $baseUrl = 'https://api.brevo.com/v3';

    public function __construct()
    {
        $this->apiKey = env('BREVO_API_KEY');
    }

    /**
     * Send email with optional attachment
     */
    public function sendEmail($to, $subject, $htmlContent, $textContent = null, $replyTo = null, $attachmentPath = null)
    {
        try {
            $emailData = [
                'sender' => [
                    'name' => env('MAIL_FROM_NAME', 'Salenga Farm'),
                    'email' => env('MAIL_FROM_ADDRESS')
                ],
                'to' => [
                    ['email' => $to]
                ],
                'subject' => $subject,
                'htmlContent' => $htmlContent
            ];

            // Add attachment if provided
            if ($attachmentPath && Storage::exists($attachmentPath)) {
                $fileContent = Storage::get($attachmentPath);
                $fileName = basename($attachmentPath);
                
                $emailData['attachment'] = [
                    [
                        'content' => base64_encode($fileContent),
                        'name' => $fileName
                    ]
                ];
            }

            $response = Http::withHeaders([
                'api-key' => $this->apiKey,
                'Content-Type' => 'application/json'
            ])->post($this->baseUrl . '/smtp/email', $emailData);

            if ($response->successful()) {
                return ['success' => true, 'messageId' => $response->json('messageId')];
            } else {
                return ['success' => false, 'error' => $response->body()];
            }

        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}
```

## 11.5 Database Migration - Plant Requests Table

**File: `database/migrations/2025_04_21_174310_create_plant_requests_table.php`**

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plant_requests', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('contact_number')->nullable();
            $table->string('company_name')->nullable();
            $table->text('company_address')->nullable();
            $table->json('items_json');
            $table->decimal('total_price', 10, 2)->nullable();
            $table->enum('status', ['pending', 'sent', 'responded', 'cancelled'])->default('pending');
            $table->enum('request_type', ['client', 'user'])->default('client');
            $table->string('pdf_path')->nullable();
            $table->timestamp('request_date')->useCurrent();
            $table->timestamp('response_sent_at')->nullable();
            $table->unsignedBigInteger('responded_by')->nullable();
            $table->timestamps();
            
            $table->foreign('responded_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plant_requests');
    }
};
```

## 11.6 JavaScript - Plant Selection for RFQ

**File: `public/js/rfq.js`**

```javascript
// Plant selection for RFQ submission
let selectedPlants = [];

function togglePlantSelection(plantId, plantName) {
    const index = selectedPlants.findIndex(p => p.id === plantId);
    
    if (index > -1) {
        // Remove plant from selection
        selectedPlants.splice(index, 1);
        updateSelectionUI(plantId, false);
    } else {
        // Add plant to selection
        selectedPlants.push({
            id: plantId,
            name: plantName,
            quantity: 1,
            height: '',
            spread: '',
            spacing: ''
        });
        updateSelectionUI(plantId, true);
    }
    
    updateSelectionCount();
}

function updateSelectionCount() {
    const count = selectedPlants.length;
    const button = document.getElementById('viewRfqBtn');
    
    if (count > 0) {
        button.textContent = `View RFQ (${count})`;
        button.style.display = 'block';
    } else {
        button.style.display = 'none';
    }
}

function submitRfqForm() {
    const formData = {
        name: document.getElementById('rfqName').value,
        email: document.getElementById('rfqEmail').value,
        contact_number: document.getElementById('rfqContact').value,
        company_name: document.getElementById('rfqCompany').value,
        items_json: JSON.stringify(selectedPlants)
    };
    
    fetch('/client-request', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('RFQ submitted successfully!');
            selectedPlants = [];
            updateSelectionCount();
            document.getElementById('rfqFormModal').style.display = 'none';
        } else {
            alert('Error submitting RFQ: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while submitting the RFQ.');
    });
}
```

## 11.7 Blade Template - Request Management Interface

**File: `resources/views/admin/requests/index.blade.php`**

```php
@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Request Management</h3>
                </div>
                <div class="card-body">
                    <!-- Navigation Tabs -->
                    <ul class="nav nav-tabs" id="requestTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="client-requests-tab" 
                                    data-bs-toggle="tab" data-bs-target="#client-requests" 
                                    type="button" role="tab">
                                Client Requests ({{ $clientRequests->count() }})
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="user-requests-tab" 
                                    data-bs-toggle="tab" data-bs-target="#user-requests" 
                                    type="button" role="tab">
                                User Inquiries ({{ $userRequests->count() }})
                            </button>
                        </li>
                    </ul>

                    <!-- Tab Content -->
                    <div class="tab-content" id="requestTabsContent">
                        <!-- Client Requests Tab -->
                        <div class="tab-pane fade show active" id="client-requests" role="tabpanel">
                            <div class="table-responsive mt-3">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Client Name</th>
                                            <th>Email</th>
                                            <th>Request Date</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($clientRequests as $request)
                                        <tr>
                                            <td>{{ $request->id }}</td>
                                            <td>{{ $request->name }}</td>
                                            <td>{{ $request->email }}</td>
                                            <td>{{ $request->request_date->format('M d, Y') }}</td>
                                            <td>
                                                <span class="badge badge-{{ $request->status === 'pending' ? 'warning' : 'success' }}">
                                                    {{ ucfirst($request->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('requests.view', $request->id) }}" 
                                                   class="btn btn-sm btn-primary">
                                                    <i class="fas fa-eye"></i> View
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- User Inquiries Tab -->
                        <div class="tab-pane fade" id="user-requests" role="tabpanel">
                            <div class="table-responsive mt-3">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>User Name</th>
                                            <th>Email</th>
                                            <th>Inquiry Date</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($userRequests as $request)
                                        <tr>
                                            <td>{{ $request->id }}</td>
                                            <td>{{ $request->name }}</td>
                                            <td>{{ $request->email }}</td>
                                            <td>{{ $request->request_date->format('M d, Y') }}</td>
                                            <td>
                                                <span class="badge badge-{{ $request->status === 'pending' ? 'warning' : 'success' }}">
                                                    {{ ucfirst($request->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('requests.view', $request->id) }}" 
                                                   class="btn btn-sm btn-primary">
                                                    <i class="fas fa-eye"></i> View
                                                </a>
                                            </td>
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
@endsection
```

---

**Note:** These code snippets demonstrate the core functionality of the system including user role management, plant inventory operations, request processing, email integration, database structure, client-side interactions, and user interface components. The code follows Laravel best practices and implements the system's key features as described in the manuscript.