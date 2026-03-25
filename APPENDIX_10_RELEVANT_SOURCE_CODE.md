Appendix 10. Relevant Source Code

Source code in User Role Management System including authentication, role checking, and access control

```php
class User extends Authenticatable
{
    protected $fillable = [
        'name', 'first_name', 'last_name', 'contact_number',
        'company_name', 'company_address', 'address', 'gender',
        'account_type', 'email', 'password', 'role', 'avatar', 'is_client',
    ];

    public function hasAdminAccess(): bool
    {
        return in_array($this->role, ['super_admin', 'admin']);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    public function isClient(): bool
    {
        return (bool)$this->is_client;
    }

    public function hasClientAccess(): bool
    {
        return (bool)$this->is_client || in_array($this->role, ['super_admin', 'admin']);
    }
}
```

Source code in Plant Inventory Management including adding, updating, deleting, and searching plant records

```php
class PlantController extends Controller
{
    public function index()
    {
        $plants = Plant::orderBy('name', 'asc')->get();
        $categories = \App\Models\Category::all();
        return view('plants.index', compact('plants', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string',
            'photo' => 'nullable|image|max:2048',
            'price' => 'nullable|numeric|min:0',
            'quantity' => 'nullable|numeric|min:0'
        ]);

        $plant = new Plant($validated);

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('plant-photos', 'public');
            $plant->photo_path = $path;
        }

        $plant->save();
        return response()->json(['message' => 'Plant added successfully!']);
    }

    public function update(Request $request, Plant $plant)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string',
            'price' => 'nullable|numeric|min:0',
            'quantity' => 'nullable|numeric|min:0'
        ]);

        $plant->update($validated);
        return response()->json(['message' => 'Plant updated successfully!']);
    }

    public function search(Request $request)
    {
        $query = $request->input('search');
        
        $plants = Plant::where(function($q) use ($query) {
            $q->where('name', 'like', "%{$query}%")
              ->orWhere('scientific_name', 'like', "%{$query}%");
        })->limit(10)->get();
        
        return response()->json($plants);
    }
}
```

Source code in Request Management System including client RFQ processing, user inquiries, and email notifications

```php
class ClientRequestController extends Controller
{
    public function index()
    {
        $clientRequests = PlantRequest::where(function($query) {
                $query->whereNull('request_type')->orWhere('request_type', 'client');
            })->orderBy('request_date', 'desc')->get();
            
        $userRequests = PlantRequest::where('request_type', 'user')
            ->orderBy('request_date', 'desc')->get();
            
        return view('admin.requests.index', compact('clientRequests', 'userRequests'));
    }

    public function store(Request $request)
    {
        $plantRequest = new PlantRequest();
        $plantRequest->email = $request->email;
        $plantRequest->name = $request->name ?? 'Guest User';
        $plantRequest->request_date = now();
        $plantRequest->due_date = now()->addDays(14);
        $plantRequest->items_json = json_decode($request->items_json, true);
        $plantRequest->status = 'pending';
        $plantRequest->save();

        $this->generatePdf($plantRequest->id);
        
        return response()->json(['message' => 'Request submitted successfully!']);
    }

    public function generatePdf($id)
    {
        $request = PlantRequest::findOrFail($id);
        
        $pdf = Pdf::loadView('pdf.rfq', compact('request'));
        $filename = "rfq_{$request->id}_" . time() . '.pdf';
        $path = "pdfs/{$filename}";
        
        Storage::put($path, $pdf->output());
        $request->update(['pdf_path' => $path]);
        
        return $path;
    }

    public function sendEmail($id)
    {
        $request = PlantRequest::findOrFail($id);
        $subject = "Plant Request #{$request->id} - Quotation from Salenga Farm";
        
        $brevoService = new BrevoEmailService();
        $emailView = view('emails.plant-request', ['request' => $request])->render();
        
        $result = $brevoService->sendEmail($request->email, $subject, $emailView, null, null, $request->pdf_path);
        
        if ($result['success']) {
            $request->update(['status' => 'sent']);
            return back()->with('success', 'Email sent successfully!');
        }
    }
}
```

---

Note: These code snippets demonstrate the core functionality of the system including user role management, plant inventory operations, and request processing with PDF generation and email notifications. The code follows Laravel best practices and implements the system's key features as described in the manuscript.