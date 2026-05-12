<?php

namespace App\Http\Controllers;

use App\Models\SiteVisit;
use App\Models\SiteVisitRequest;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SiteVisitController extends Controller
{
    /**
     * Display the site visits map and list
     */
    public function index()
    {
        // Get all site visits grouped by status
        $allSiteVisits = SiteVisit::orderBy('visit_date', 'desc')->get();
        $pendingSiteVisits = SiteVisit::where('status', 'pending')->orderBy('visit_date', 'desc')->get();
        $followUpSiteVisits = SiteVisit::where('status', 'follow_up')->orderBy('visit_date', 'desc')->get();
        $completedSiteVisits = SiteVisit::where('status', 'completed')->orderBy('visit_date', 'desc')->get();
        
        return view('site-visits.index', compact('allSiteVisits', 'pendingSiteVisits', 'followUpSiteVisits', 'completedSiteVisits'));
    }

    /**
     * Client: list my linked site visits
     */
    public function myVisits()
    {
        $user = Auth::user();
        $siteVisits = SiteVisit::where('user_id', $user->id)->orderBy('visit_date', 'desc')->get();
        return view('site-visits.my', compact('siteVisits'));
    }

    /**
     * Upload a file for a specific Client's Data item
     */
    public function uploadClientData(Request $request, SiteVisit $siteVisit, string $itemKey)
    {
        // Authorization: linked client or admin can upload
        $user = Auth::user();
        $isLinkedClient = $siteVisit->user_id && $siteVisit->user_id === $user->id;
        $isAdmin = $user->hasAdminAccess();
        if (!$isLinkedClient && !$isAdmin) {
            abort(403);
        }
        // Hybrid gating: clients can only upload when open; admin bypasses
        if (!$isAdmin) {
            $isOpen = ($siteVisit->client_data_open ?? false) || ($siteVisit->status === 'completed');
            if (!$isOpen) {
                abort(403);
            }
        }
        // Determine allowed mimes (Drone Map may allow video)
        $isDrone = strtolower($itemKey) === 'drone_map';
        $mimes = $isDrone ? 'pdf,jpg,jpeg,png,mp4,mov' : 'pdf,jpg,jpeg,png';
        $validated = $request->validate([
            'file' => 'required|file|mimes:' . $mimes . '|max:20480', // 20MB
        ]);

        $file = $validated['file'];
        $path = $file->store('site-visits', 'public');
        $entry = [
            'path' => $path,
            'original_name' => $file->getClientOriginalName(),
            'type' => $file->getMimeType(),
            'uploaded_by' => Auth::id(),
            'uploaded_at' => now()->toDateTimeString(),
        ];

        $clientData = $siteVisit->client_data_checklist ?? [];
        // Ensure array (handle legacy string JSON)
        if (!is_array($clientData)) {
            $clientData = is_string($clientData) ? (json_decode($clientData, true) ?: []) : [];
        }
        $current = $clientData[$itemKey] ?? [];
        $current[] = $entry;
        $clientData[$itemKey] = $current;

        // Set status to submitted when client uploads
        $statuses = $siteVisit->client_data_statuses ?? [];
        // Ensure array (handle legacy string JSON)
        if (!is_array($statuses)) {
            $statuses = is_string($statuses) ? (json_decode($statuses, true) ?: []) : [];
        }
        $statuses[$itemKey] = [
            'status' => 'submitted',
            'by' => Auth::id(),
            'at' => now()->toDateTimeString(),
            'note' => null,
        ];

        $siteVisit->client_data_checklist = $clientData;
        $siteVisit->client_data_statuses = $statuses;
        $siteVisit->save();

        return back()->with('success', 'File uploaded for ' . str_replace('_', ' ', $itemKey));
    }

    /**
     * Delete a file from a specific Client's Data item
     */
    public function deleteClientData(SiteVisit $siteVisit, string $itemKey, int $fileIndex)
    {
        // Authorization: linked client or admin can delete
        $user = Auth::user();
        $isLinkedClient = $siteVisit->user_id && $siteVisit->user_id === $user->id;
        $isAdmin = $user->hasAdminAccess();
        if (!$isLinkedClient && !$isAdmin) {
            abort(403);
        }
        // Hybrid gating: clients can only delete when open; admin bypasses
        if (!$isAdmin) {
            $isOpen = ($siteVisit->client_data_open ?? false) || ($siteVisit->status === 'completed');
            if (!$isOpen) {
                abort(403);
            }
        }

        $clientData = $siteVisit->client_data_checklist ?? [];
        // Ensure array (handle legacy string JSON)
        if (!is_array($clientData)) {
            $clientData = is_string($clientData) ? (json_decode($clientData, true) ?: []) : [];
        }
        
        $files = $clientData[$itemKey] ?? [];
        
        // Check if file index exists
        if (!isset($files[$fileIndex])) {
            return back()->with('error', 'File not found');
        }
        
        // Delete the file from storage
        $filePath = $files[$fileIndex]['path'] ?? null;
        if ($filePath && Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);
        }
        
        // Remove the file from the array
        array_splice($files, $fileIndex, 1);
        $clientData[$itemKey] = $files;
        
        // Update status if no files remain
        if (empty($files)) {
            $statuses = $siteVisit->client_data_statuses ?? [];
            if (!is_array($statuses)) {
                $statuses = is_string($statuses) ? (json_decode($statuses, true) ?: []) : [];
            }
            $statuses[$itemKey] = [
                'status' => 'missing',
                'by' => Auth::id(),
                'at' => now()->toDateTimeString(),
                'note' => null,
            ];
            $siteVisit->client_data_statuses = $statuses;
        }
        
        $siteVisit->client_data_checklist = $clientData;
        $siteVisit->save();

        return back()->with('success', 'File deleted successfully');
    }

    /**
     * Bulk delete files from Client's Data
     */
    public function bulkDeleteClientData(Request $request, SiteVisit $siteVisit)
    {
        // Authorization: linked client or admin can delete
        $user = Auth::user();
        $isLinkedClient = $siteVisit->user_id && $siteVisit->user_id === $user->id;
        $isAdmin = $user->hasAdminAccess();
        if (!$isLinkedClient && !$isAdmin) {
            abort(403);
        }
        // Hybrid gating: clients can only delete when open; admin bypasses
        if (!$isAdmin) {
            $isOpen = ($siteVisit->client_data_open ?? false) || ($siteVisit->status === 'completed');
            if (!$isOpen) {
                abort(403);
            }
        }

        $validated = $request->validate([
            'files' => 'required|array',
            'files.*.item_key' => 'required|string',
            'files.*.file_index' => 'required|integer',
        ]);

        $clientData = $siteVisit->client_data_checklist ?? [];
        if (!is_array($clientData)) {
            $clientData = is_string($clientData) ? (json_decode($clientData, true) ?: []) : [];
        }

        $deletedCount = 0;
        $itemsToUpdateStatus = [];

        // Group files by item_key and sort by file_index descending to avoid index shifting issues
        $filesByItem = [];
        foreach ($validated['files'] as $file) {
            $filesByItem[$file['item_key']][] = (int)$file['file_index'];
        }

        foreach ($filesByItem as $itemKey => $fileIndices) {
            rsort($fileIndices); // Sort descending to delete from end to start
            
            $files = $clientData[$itemKey] ?? [];
            
            foreach ($fileIndices as $fileIndex) {
                if (isset($files[$fileIndex])) {
                    // Delete the file from storage
                    $filePath = $files[$fileIndex]['path'] ?? null;
                    if ($filePath && Storage::disk('public')->exists($filePath)) {
                        Storage::disk('public')->delete($filePath);
                    }
                    
                    // Remove from array
                    array_splice($files, $fileIndex, 1);
                    $deletedCount++;
                }
            }
            
            $clientData[$itemKey] = $files;
            
            // Track items that now have no files
            if (empty($files)) {
                $itemsToUpdateStatus[] = $itemKey;
            }
        }

        // Update statuses for items with no files
        if (!empty($itemsToUpdateStatus)) {
            $statuses = $siteVisit->client_data_statuses ?? [];
            if (!is_array($statuses)) {
                $statuses = is_string($statuses) ? (json_decode($statuses, true) ?: []) : [];
            }
            
            foreach ($itemsToUpdateStatus as $itemKey) {
                $statuses[$itemKey] = [
                    'status' => 'missing',
                    'by' => Auth::id(),
                    'at' => now()->toDateTimeString(),
                    'note' => null,
                ];
            }
            
            $siteVisit->client_data_statuses = $statuses;
        }

        $siteVisit->client_data_checklist = $clientData;
        $siteVisit->save();

        return back()->with('success', $deletedCount . ' file(s) deleted successfully');
    }

    /**
     * Set status for a Client's Data item (admin)
     */
    public function setClientDataItemStatus(Request $request, SiteVisit $siteVisit, string $itemKey)
    {
        // Authorization: admin only
        if (!Auth::user()->hasAdminAccess()) {
            abort(403);
        }
        $validated = $request->validate([
            'status' => 'required|in:received,rejected,missing',
            'note' => 'nullable|string|max:500',
        ]);

        $statuses = $siteVisit->client_data_statuses ?? [];
        if (!is_array($statuses)) {
            $statuses = is_string($statuses) ? (json_decode($statuses, true) ?: []) : [];
        }
        $statuses[$itemKey] = [
            'status' => $validated['status'],
            'by' => Auth::id(),
            'at' => now()->toDateTimeString(),
            'note' => $validated['note'] ?? null,
        ];
        $siteVisit->client_data_statuses = $statuses;
        $siteVisit->save();

        return back()->with('success', 'Updated status for ' . str_replace('_', ' ', $itemKey));
    }

    /**
     * Upload a file for a specific Proposal item (admin)
     */
    public function uploadProposalItem(Request $request, SiteVisit $siteVisit, string $itemKey)
    {
        // Authorization: admin only
        if (!Auth::user()->hasAdminAccess()) {
            abort(403);
        }
        $validated = $request->validate([
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png,xlsx|max:20480',
        ]);

        $file = $validated['file'];
        $path = $file->store('site-visits', 'public');
        $entry = [
            'path' => $path,
            'original_name' => $file->getClientOriginalName(),
            'type' => $file->getMimeType(),
            'uploaded_by' => Auth::id(),
            'uploaded_at' => now()->toDateTimeString(),
        ];

        $proposal = $siteVisit->proposal_checklist ?? [];
        if (!is_array($proposal)) {
            $proposal = is_string($proposal) ? (json_decode($proposal, true) ?: []) : [];
        }
        $current = $proposal[$itemKey] ?? [];
        $current[] = $entry;
        $proposal[$itemKey] = $current;

        // Mark as uploaded
        $statuses = $siteVisit->proposal_item_statuses ?? [];
        if (!is_array($statuses)) {
            $statuses = is_string($statuses) ? (json_decode($statuses, true) ?: []) : [];
        }
        $statuses[$itemKey] = [
            'status' => 'uploaded',
            'by' => Auth::id(),
            'at' => now()->toDateTimeString(),
            'note' => null,
        ];

        $siteVisit->proposal_checklist = $proposal;
        $siteVisit->proposal_item_statuses = $statuses;
        $siteVisit->save();

        return back()->with('success', 'Proposal file uploaded for ' . str_replace('_', ' ', $itemKey));
    }

    /**
     * Client proposal approval decision
     */
    public function setProposalApproval(Request $request, SiteVisit $siteVisit)
    {
        // Authorization: linked client or admin
        $user = Auth::user();
        $isLinkedClient = $siteVisit->user_id && $siteVisit->user_id === $user->id;
        if (!$isLinkedClient && !$user->hasAdminAccess()) {
            abort(403);
        }
        $validated = $request->validate([
            'decision' => 'required|in:approved,changes_requested',
            'comment' => 'nullable|string|max:1000',
        ]);

        $approval = [
            'status' => $validated['decision'],
            'comment' => $validated['comment'] ?? null,
            'by' => Auth::id(),
            'at' => now()->toDateTimeString(),
        ];
        $siteVisit->proposal_approval = $approval;
        $siteVisit->save();

        return back()->with('success', 'Proposal ' . ($validated['decision'] === 'approved' ? 'approved' : 'sent back for changes'));
    }

    /**
     * Get all site visits as JSON for map display
     */
    public function getVisitsJson()
    {
        $siteVisits = SiteVisit::select([
            'id', 'latitude', 'longitude', 'client', 'location', 
            'visit_date', 'status', 'location_address'
        ])->get();

        return response()->json($siteVisits);
    }

    /**
     * Show the form for creating a new site visit
     */
    public function create(Request $request)
    {
        $latitude = $request->get('lat');
        $longitude = $request->get('lng');
        $address = $request->get('address');
        $clients = User::where('is_client', true)
            ->orderBy('email')
            ->get(['id','first_name','last_name','email','contact_number']);

        return view('site-visits.create', compact('latitude', 'longitude', 'address', 'clients'));
    }

    /**
     * Store a newly created site visit
     */
    public function store(Request $request)
    {
        try {
            // Log incoming request for debugging
            Log::info('Site Visit Store Request', [
                'has_latitude' => $request->has('latitude'),
                'has_longitude' => $request->has('longitude'),
                'latitude_value' => $request->input('latitude'),
                'longitude_value' => $request->input('longitude'),
                'has_client' => $request->has('client'),
                'has_email' => $request->has('email'),
                'has_user_id' => $request->has('user_id'),
                'user_id_value' => $request->input('user_id'),
            ]);

            $validated = $request->validate([
                'user_id' => 'nullable|exists:users,id',
                'latitude' => 'required|numeric|between:-90,90',
                'longitude' => 'required|numeric|between:-180,180',
                'location_address' => 'nullable|string|max:500',
                'client' => 'required_without:user_id|nullable|string|max:255',
                'contact_number' => 'required|string|max:20',
                'email' => 'required_without:user_id|email|max:255',
                'job_no' => 'nullable|string|max:50',
                'project_code' => 'nullable|string|max:50',
                'project_no' => 'nullable|string|max:50',
                'location' => 'required|string|max:255',
                'landscape_area' => 'nullable|string|max:100',
                'site_inspector' => 'required|string|max:255',
                'visit_date' => 'required|date',
                'physical_factors' => 'nullable|array',
                'physical_factors.prevailing_winds.value' => 'nullable|in:yes,no',
                'physical_factors.prevailing_winds.remarks' => 'nullable|string|max:255',
                'physical_factors.solar_orientation.value' => 'nullable|in:yes,no',
                'physical_factors.solar_orientation.remarks' => 'nullable|string|max:255',
                'physical_factors.humidity.value' => 'nullable|in:yes,no',
                'physical_factors.humidity.remarks' => 'nullable|string|max:255',
                'physical_factors.notes' => 'nullable|string|max:1000',
                'topography' => 'nullable|array',
                'topography.legal_properties_description.value' => 'nullable|in:yes,no',
                'topography.legal_properties_description.remarks' => 'nullable|string|max:255',
                'topography.topographic_maps_aerial_photos.value' => 'nullable|in:yes,no',
                'topography.topographic_maps_aerial_photos.remarks' => 'nullable|string|max:255',
                'topography.existing_access_circulation.value' => 'nullable|in:yes,no',
                'topography.existing_access_circulation.remarks' => 'nullable|string|max:255',
                'topography.vegetation.value' => 'nullable|in:yes,no',
                'topography.vegetation.remarks' => 'nullable|string|max:255',
                'topography.existing_water_bodies.value' => 'nullable|in:yes,no',
                'topography.existing_water_bodies.remarks' => 'nullable|string|max:255',
                'topography.drainage_canals.value' => 'nullable|in:yes,no',
                'topography.drainage_canals.remarks' => 'nullable|string|max:255',
                'topography.existing_waterway.value' => 'nullable|in:yes,no',
                'topography.existing_waterway.remarks' => 'nullable|string|max:255',
                'topography.unique_site_features.value' => 'nullable|in:yes,no',
                'topography.unique_site_features.remarks' => 'nullable|string|max:255',
                'topography.notes' => 'nullable|string|max:1000',
                'geotechnical_soils' => 'nullable|array',
                'geotechnical_soils.basic_soil_type.value' => 'nullable|in:yes,no',
                'geotechnical_soils.basic_soil_type.remarks' => 'nullable|string|max:255',
                'geotechnical_soils.soil_conditions.value' => 'nullable|in:yes,no',
                'geotechnical_soils.soil_conditions.remarks' => 'nullable|string|max:255',
                'geotechnical_soils.earthfill_requirement.value' => 'nullable|in:yes,no',
                'geotechnical_soils.earthfill_requirement.remarks' => 'nullable|string|max:255',
                'geotechnical_soils.notes' => 'nullable|string|max:1000',
                'utilities' => 'nullable|array',
                'utilities.potable_water.value' => 'nullable|in:yes,no',
                'utilities.potable_water.remarks' => 'nullable|string|max:255',
                'utilities.electricity.value' => 'nullable|in:yes,no',
                'utilities.electricity.remarks' => 'nullable|string|max:255',
                'utilities.storm_drainage.value' => 'nullable|in:yes,no',
                'utilities.storm_drainage.remarks' => 'nullable|string|max:255',
                'utilities.notes' => 'nullable|string|max:1000',
                'immediate_surroundings' => 'nullable|array',
                'immediate_surroundings.neighborhood_structures.value' => 'nullable|in:yes,no',
                'immediate_surroundings.neighborhood_structures.remarks' => 'nullable|string|max:255',
                'immediate_surroundings.notes' => 'nullable|string|max:1000',
                'tools_checklist' => 'nullable|array',
                // Tools: Safety
                'tools_checklist.safety.vest' => 'nullable|boolean',
                'tools_checklist.safety.hard_hat' => 'nullable|boolean',
                'tools_checklist.safety.safety_shoes' => 'nullable|boolean',
                'tools_checklist.safety.mask' => 'nullable|boolean',
                'tools_checklist.safety.face_shield_medical_cert' => 'nullable|boolean',
                // Tools: Documentation
                'tools_checklist.documentation.scale' => 'nullable|boolean',
                'tools_checklist.documentation.steel_tape' => 'nullable|boolean',
                'tools_checklist.documentation.tape_measures_long' => 'nullable|boolean',
                'tools_checklist.documentation.tape_measures_short' => 'nullable|boolean',
                'tools_checklist.documentation.camera' => 'nullable|boolean',
                // Tools: Drawing
                'tools_checklist.drawing.clip_board' => 'nullable|boolean',
                'tools_checklist.drawing.plans' => 'nullable|boolean',
                'tools_checklist.drawing.bond_papers' => 'nullable|boolean',
                'tools_checklist.drawing.pens' => 'nullable|boolean',
                'additional_services' => 'nullable|array',
                'additional_services.land_preparation.value' => 'nullable|in:yes,no',
                'additional_services.land_preparation.remarks' => 'nullable|string|max:255',
                'additional_services.grading.value' => 'nullable|in:yes,no',
                'additional_services.grading.remarks' => 'nullable|string|max:255',
                'additional_services.leveling.value' => 'nullable|in:yes,no',
                'additional_services.leveling.remarks' => 'nullable|string|max:255',
                'additional_services.stacking.value' => 'nullable|in:yes,no',
                'additional_services.stacking.remarks' => 'nullable|string|max:255',
                'additional_services.notes' => 'nullable|string|max:1000',
                'client_data_checklist' => 'nullable|array',
                'proposal_checklist' => 'nullable|array',
                'status' => 'required|in:pending,completed,follow_up',
                'notes' => 'nullable|string',
                'terms_and_conditions' => 'nullable|string',
                'design_quotation' => 'nullable|string',
                'media_files.*' => 'nullable|file|mimes:jpg,jpeg,png,mp4,mov|max:10240',
                // Multi-file uploads: Client Data and Proposal Documents
                'clients_data' => 'nullable|array',
                'clients_data.*' => 'nullable|array',
                'clients_data.*.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
                'proposal_documents' => 'nullable|array',
                'proposal_documents.*' => 'nullable|array',
                'proposal_documents.*.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240'
            ]);

            // Hybrid gating toggle
            $validated['client_data_open'] = $request->boolean('client_data_open');

            // If an existing user is selected, backfill client info when missing
            if (!empty($validated['user_id'])) {
                $user = User::find($validated['user_id']);
                if ($user) {
                    // Only override when empty to respect manual edits
                    if (empty($validated['client'])) {
                        $displayName = $user->name ?? trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? ''));
                        if (!empty($displayName)) {
                            $validated['client'] = $displayName;
                        }
                    }
                    if (empty($validated['contact_number']) && !empty($user->contact_number)) {
                        $validated['contact_number'] = $user->contact_number;
                    }
                    if (empty($validated['email'])) {
                        $validated['email'] = $user->email;
                    }
                }
            }

            // Handle file uploads
            $mediaFiles = [];
            if ($request->hasFile('media_files')) {
                foreach ($request->file('media_files') as $file) {
                    $path = $file->store('site-visits', 'public');
                    $mediaFiles[] = [
                        'path' => $path,
                        'original_name' => $file->getClientOriginalName(),
                        'type' => $file->getMimeType()
                    ];
                }
            }
            $validated['media_files'] = $mediaFiles;

            // Handle Client Data multi-file uploads
            $clientDataFiles = [];
            if ($request->hasFile('clients_data')) {
                foreach ($request->file('clients_data') as $itemKey => $files) {
                    foreach ($files as $file) {
                        if (!$file) { continue; }
                        $path = $file->store('site-visits', 'public');
                        $clientDataFiles[$itemKey][] = [
                            'path' => $path,
                            'original_name' => $file->getClientOriginalName(),
                            'type' => $file->getMimeType()
                        ];
                    }
                }
            }
            $validated['client_data_checklist'] = $clientDataFiles;

            // Handle Proposal Documents multi-file uploads
            $proposalDocFiles = [];
            if ($request->hasFile('proposal_documents')) {
                foreach ($request->file('proposal_documents') as $itemKey => $files) {
                    foreach ($files as $file) {
                        if (!$file) { continue; }
                        $path = $file->store('site-visits', 'public');
                        $proposalDocFiles[$itemKey][] = [
                            'path' => $path,
                            'original_name' => $file->getClientOriginalName(),
                            'type' => $file->getMimeType()
                        ];
                    }
                }
            }
            $validated['proposal_checklist'] = $proposalDocFiles;

            $siteVisit = SiteVisit::create($validated);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Site visit created successfully',
                    'site_visit' => $siteVisit
                ]);
            }

            return redirect()->route('site-visits.index')
                ->with('success', 'Site visit created successfully');

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error creating site visit', [
                'errors' => $e->errors(),
                'message' => $e->getMessage()
            ]);
            
            return back()->withErrors($e->errors())->withInput()
                ->with('error', 'Please check the form for errors.');
                
        } catch (\Exception $e) {
            Log::error('Error creating site visit', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error creating site visit: ' . $e->getMessage()
                ], 500);
            }

            return back()->withInput()
                ->with('error', 'Error creating site visit: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified site visit
     */
    public function show(SiteVisit $siteVisit)
    {
        return view('site-visits.show', compact('siteVisit'));
    }

    /**
     * Client-accessible view: linked client or admin can view the details page
     */
    public function showForClient(SiteVisit $siteVisit)
    {
        $user = Auth::user();
        $isLinkedClient = $siteVisit->user_id && $siteVisit->user_id === $user->id;
        if (!$isLinkedClient && !$user->hasAdminAccess()) {
            abort(403);
        }

        return view('site-visits.show', compact('siteVisit'));
    }

    /**
     * Client Data: list of visits open for client uploads (hybrid gating)
     * Only accessible by clients
     */
    public function clientDataIndex()
    {
        $user = Auth::user();
        
        // Only clients can access this page
        if (!$user->isClient()) {
            abort(403, 'Access denied. This page is only available for clients.');
        }
        
        // Check if profile is complete - redirect to teaser if not
        if (!$user->isProfileComplete()) {
            return view('site-visit-teaser');
        }
        
        $siteVisits = SiteVisit::where('user_id', $user->id)
            ->where(function($q){
                $q->where('status', 'completed')
                  ->orWhere('client_data_open', true);
            })
            ->orderBy('visit_date', 'desc')
            ->get();

        return view('client-data.index', compact('siteVisits'));
    }

    /**
     * Client Data: show single visit for client uploads (hybrid gating)
     * Only accessible by clients who own the visit or admins
     */
    public function clientDataShow(SiteVisit $siteVisit)
    {
        $user = Auth::user();
        
        // Only clients or admins can access
        if (!$user->isClient() && !$user->hasAdminAccess()) {
            abort(403, 'Access denied. This page is only available for clients.');
        }
        
        $isLinkedClient = $siteVisit->user_id && $siteVisit->user_id === $user->id;
        if (!$isLinkedClient && !$user->hasAdminAccess()) {
            abort(403);
        }

        $isOpen = ($siteVisit->client_data_open ?? false) || ($siteVisit->status === 'completed');
        return view('client-data.show', compact('siteVisit', 'isOpen'));
    }

    /**
     * Quick update of visit status (admin only), optionally toggling client_data_open
     */
    public function updateStatus(Request $request, SiteVisit $siteVisit)
    {
        $user = Auth::user();
        if (!$user || !$user->hasAdminAccess()) {
            abort(403);
        }

        $data = $request->validate([
            'status' => 'required|in:pending,completed,follow_up',
            'client_data_open' => 'nullable|boolean',
        ]);

        $siteVisit->status = $data['status'];
        if ($request->has('client_data_open')) {
            $siteVisit->client_data_open = (bool)$data['client_data_open'];
        }
        $siteVisit->save();

        return back()->with('success', 'Site Visit status updated.');
    }

    /**
     * Show the form for editing the specified site visit
     */
    public function edit(SiteVisit $siteVisit)
    {
        $clients = User::where('is_client', true)
            ->orderBy('first_name')
            ->get(['id','first_name','last_name','email','contact_number']);
        return view('site-visits.edit', compact('siteVisit', 'clients'));
    }

    /**
     * Update the specified site visit
     */
    public function update(Request $request, SiteVisit $siteVisit)
    {
        try {
            $validated = $request->validate([
                'user_id' => 'nullable|exists:users,id',
                'latitude' => 'required|numeric|between:-90,90',
                'longitude' => 'required|numeric|between:-180,180',
                'location_address' => 'nullable|string|max:500',
                'client' => 'required_without:user_id|string|max:255',
                'contact_number' => 'required|string|max:20',
                'email' => 'required_without:user_id|email|max:255',
                'job_no' => 'nullable|string|max:50',
                'project_code' => 'nullable|string|max:50',
                'project_no' => 'nullable|string|max:50',
                'location' => 'required|string|max:255',
                'landscape_area' => 'nullable|string|max:100',
                'site_inspector' => 'required|string|max:255',
                'visit_date' => 'required|date',
                'physical_factors' => 'nullable|array',
                'physical_factors.prevailing_winds.value' => 'nullable|in:yes,no',
                'physical_factors.prevailing_winds.remarks' => 'nullable|string|max:255',
                'physical_factors.solar_orientation.value' => 'nullable|in:yes,no',
                'physical_factors.solar_orientation.remarks' => 'nullable|string|max:255',
                'physical_factors.humidity.value' => 'nullable|in:yes,no',
                'physical_factors.humidity.remarks' => 'nullable|string|max:255',
                'physical_factors.notes' => 'nullable|string|max:1000',
                'topography' => 'nullable|array',
                'topography.legal_properties_description.value' => 'nullable|in:yes,no',
                'topography.legal_properties_description.remarks' => 'nullable|string|max:255',
                'topography.topographic_maps_aerial_photos.value' => 'nullable|in:yes,no',
                'topography.topographic_maps_aerial_photos.remarks' => 'nullable|string|max:255',
                'topography.existing_access_circulation.value' => 'nullable|in:yes,no',
                'topography.existing_access_circulation.remarks' => 'nullable|string|max:255',
                'topography.vegetation.value' => 'nullable|in:yes,no',
                'topography.vegetation.remarks' => 'nullable|string|max:255',
                'topography.existing_water_bodies.value' => 'nullable|in:yes,no',
                'topography.existing_water_bodies.remarks' => 'nullable|string|max:255',
                'topography.drainage_canals.value' => 'nullable|in:yes,no',
                'topography.drainage_canals.remarks' => 'nullable|string|max:255',
                'topography.existing_waterway.value' => 'nullable|in:yes,no',
                'topography.existing_waterway.remarks' => 'nullable|string|max:255',
                'topography.unique_site_features.value' => 'nullable|in:yes,no',
                'topography.unique_site_features.remarks' => 'nullable|string|max:255',
                'topography.notes' => 'nullable|string|max:1000',
                'geotechnical_soils' => 'nullable|array',
                'geotechnical_soils.basic_soil_type.value' => 'nullable|in:yes,no',
                'geotechnical_soils.basic_soil_type.remarks' => 'nullable|string|max:255',
                'geotechnical_soils.soil_conditions.value' => 'nullable|in:yes,no',
                'geotechnical_soils.soil_conditions.remarks' => 'nullable|string|max:255',
                'geotechnical_soils.earthfill_requirement.value' => 'nullable|in:yes,no',
                'geotechnical_soils.earthfill_requirement.remarks' => 'nullable|string|max:255',
                'geotechnical_soils.notes' => 'nullable|string|max:1000',
                'utilities' => 'nullable|array',
                'utilities.potable_water.value' => 'nullable|in:yes,no',
                'utilities.potable_water.remarks' => 'nullable|string|max:255',
                'utilities.electricity.value' => 'nullable|in:yes,no',
                'utilities.electricity.remarks' => 'nullable|string|max:255',
                'utilities.storm_drainage.value' => 'nullable|in:yes,no',
                'utilities.storm_drainage.remarks' => 'nullable|string|max:255',
                'utilities.notes' => 'nullable|string|max:1000',
                'immediate_surroundings' => 'nullable|array',
                'immediate_surroundings.neighborhood_structures.value' => 'nullable|in:yes,no',
                'immediate_surroundings.neighborhood_structures.remarks' => 'nullable|string|max:255',
                'immediate_surroundings.notes' => 'nullable|string|max:1000',
                'tools_checklist' => 'nullable|array',
                // Tools: Safety
                'tools_checklist.safety.vest' => 'nullable|boolean',
                'tools_checklist.safety.hard_hat' => 'nullable|boolean',
                'tools_checklist.safety.safety_shoes' => 'nullable|boolean',
                'tools_checklist.safety.mask' => 'nullable|boolean',
                'tools_checklist.safety.face_shield_medical_cert' => 'nullable|boolean',
                // Tools: Documentation
                'tools_checklist.documentation.scale' => 'nullable|boolean',
                'tools_checklist.documentation.steel_tape' => 'nullable|boolean',
                'tools_checklist.documentation.tape_measures_long' => 'nullable|boolean',
                'tools_checklist.documentation.tape_measures_short' => 'nullable|boolean',
                'tools_checklist.documentation.camera' => 'nullable|boolean',
                // Tools: Drawing
                'tools_checklist.drawing.clip_board' => 'nullable|boolean',
                'tools_checklist.drawing.plans' => 'nullable|boolean',
                'tools_checklist.drawing.bond_papers' => 'nullable|boolean',
                'tools_checklist.drawing.pens' => 'nullable|boolean',
                // Additional Services
                'additional_services' => 'nullable|array',
                'additional_services.land_preparation.value' => 'nullable|in:yes,no',
                'additional_services.land_preparation.remarks' => 'nullable|string|max:255',
                'additional_services.grading.value' => 'nullable|in:yes,no',
                'additional_services.grading.remarks' => 'nullable|string|max:255',
                'additional_services.leveling.value' => 'nullable|in:yes,no',
                'additional_services.leveling.remarks' => 'nullable|string|max:255',
                'additional_services.stacking.value' => 'nullable|in:yes,no',
                'additional_services.stacking.remarks' => 'nullable|string|max:255',
                'additional_services.notes' => 'nullable|string|max:1000',
                // Other checklists
                'client_data_checklist' => 'nullable|array',
                'proposal_checklist' => 'nullable|array',
                // Status and notes
                'status' => 'required|in:pending,completed,follow_up',
                'notes' => 'nullable|string',
                'media_files.*' => 'nullable|file|mimes:jpg,jpeg,png,mp4,mov|max:10240',
                // Multi-file uploads: Client Data and Proposal Documents
                'clients_data' => 'nullable|array',
                'clients_data.*' => 'nullable|array',
                'clients_data.*.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
                'proposal_documents' => 'nullable|array',
                'proposal_documents.*' => 'nullable|array',
                'proposal_documents.*.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240'
            ]);

            // If an existing user is selected, backfill missing client info
            if (!empty($validated['user_id'])) {
                $user = User::find($validated['user_id']);
                if ($user) {
                    if (empty($validated['client'])) {
                        $displayName = $user->name ?? trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? ''));
                        if (!empty($displayName)) {
                            $validated['client'] = $displayName;
                        }
                    }
                    if (empty($validated['contact_number']) && !empty($user->contact_number)) {
                        $validated['contact_number'] = $user->contact_number;
                    }
                    if (empty($validated['email'])) {
                        $validated['email'] = $user->email;
                    }
                }
            }

            // Handle new file uploads (general media)
            $existingFiles = $siteVisit->media_files ?? [];
            if ($request->hasFile('media_files')) {
                foreach ($request->file('media_files') as $file) {
                    $path = $file->store('site-visits', 'public');
                    $existingFiles[] = [
                        'path' => $path,
                        'original_name' => $file->getClientOriginalName(),
                        'type' => $file->getMimeType()
                    ];
                }
            }
            $validated['media_files'] = $existingFiles;

            // Handle Client Data multi-file uploads (merge)
            $existingClientData = $siteVisit->client_data_checklist ?? [];
            if ($request->hasFile('clients_data')) {
                foreach ($request->file('clients_data') as $itemKey => $files) {
                    foreach ($files as $file) {
                        if (!$file) { continue; }
                        $path = $file->store('site-visits', 'public');
                        $existingClientData[$itemKey][] = [
                            'path' => $path,
                            'original_name' => $file->getClientOriginalName(),
                            'type' => $file->getMimeType()
                        ];
                    }
                }
            }
            $validated['client_data_checklist'] = $existingClientData;

            // Handle Proposal Documents multi-file uploads (merge)
            $existingProposalDocs = $siteVisit->proposal_checklist ?? [];
            if ($request->hasFile('proposal_documents')) {
                foreach ($request->file('proposal_documents') as $itemKey => $files) {
                    foreach ($files as $file) {
                        if (!$file) { continue; }
                        $path = $file->store('site-visits', 'public');
                        $existingProposalDocs[$itemKey][] = [
                            'path' => $path,
                            'original_name' => $file->getClientOriginalName(),
                            'type' => $file->getMimeType()
                        ];
                    }
                }
            }
            $validated['proposal_checklist'] = $existingProposalDocs;

            $siteVisit->update($validated);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Site visit updated successfully',
                    'site_visit' => $siteVisit
                ]);
            }

            return redirect()->route('site-visits.index')
                ->with('success', 'Site visit updated successfully');

        } catch (\Exception $e) {
            Log::error('Error updating site visit: ' . $e->getMessage());
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error updating site visit: ' . $e->getMessage()
                ], 500);
            }

            return back()->withInput()
                ->with('error', 'Error updating site visit. Please try again.');
        }
    }

    /**
     * Remove the specified site visit
     */
    public function destroy(SiteVisit $siteVisit)
    {
        try {
            // Delete associated media files
            if ($siteVisit->media_files) {
                foreach ($siteVisit->media_files as $file) {
                    if (isset($file['path'])) {
                        Storage::disk('public')->delete($file['path']);
                    }
                }
            }

            $siteVisit->delete();

            return response()->json([
                'success' => true,
                'message' => 'Site visit deleted successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Error deleting site visit: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error deleting site visit: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get site visit data for JSON response
     */
    public function getVisitData(SiteVisit $siteVisit)
    {
        return response()->json($siteVisit);
    }

    /**
     * Delete a specific media file
     */
    public function deleteMediaFile(SiteVisit $siteVisit, Request $request)
    {
        try {
            $fileIndex = $request->get('file_index');
            $mediaFiles = $siteVisit->media_files ?? [];

            if (isset($mediaFiles[$fileIndex])) {
                // Delete the file from storage
                Storage::disk('public')->delete($mediaFiles[$fileIndex]['path']);
                
                // Remove from array
                unset($mediaFiles[$fileIndex]);
                
                // Reindex array
                $mediaFiles = array_values($mediaFiles);
                
                // Update the model
                $siteVisit->update(['media_files' => $mediaFiles]);

                return response()->json([
                    'success' => true,
                    'message' => 'File deleted successfully'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'File not found'
            ], 404);

        } catch (\Exception $e) {
            Log::error('Error deleting media file: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error deleting file: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display site visit requests (admin only)
     */
    public function requestsIndex()
    {
        if (!Auth::user()->hasAdminAccess()) {
            abort(403);
        }

        $pendingRequests = SiteVisitRequest::with(['user', 'reviewer'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        $approvedRequests = SiteVisitRequest::with(['user', 'reviewer'])
            ->where('status', 'approved')
            ->orderBy('reviewed_at', 'desc')
            ->get();

        $rejectedRequests = SiteVisitRequest::with(['user', 'reviewer'])
            ->where('status', 'rejected')
            ->orderBy('reviewed_at', 'desc')
            ->get();

        return view('site-visit-requests.index', compact('pendingRequests', 'approvedRequests', 'rejectedRequests'));
    }

    /**
     * Show the form for creating a new site visit request (client only)
     */
    public function createRequest()
    {
        $user = Auth::user();
        
        if (!$user->isClient()) {
            abort(403, 'Only clients can request site visits.');
        }

        // Check if profile is complete - redirect to teaser if not
        if (!$user->isProfileComplete()) {
            return view('site-visit-teaser');
        }

        return view('site-visit-requests.create');
    }

    /**
     * Store a new site visit request (client only)
     */
    public function storeRequest(Request $request)
    {
        $user = Auth::user();
        
        if (!$user->isClient()) {
            abort(403, 'Only clients can request site visits.');
        }

        try {
            $validated = $request->validate([
                'preferred_date' => 'required|date|after_or_equal:today',
                'preferred_time' => 'nullable',
                'property_address' => 'required|string|max:500',
                'property_size' => 'nullable|string|max:100',
                'current_condition' => 'nullable|string|max:100',
                'project_description' => 'required|string|max:2000',
                'special_requirements' => 'nullable|string|max:1000',
                'photos.*' => 'nullable|image|mimes:jpg,jpeg,png|max:5120', // 5MB
            ]);

            // Handle photo uploads
            $photos = [];
            if ($request->hasFile('photos')) {
                $uploadedPhotos = $request->file('photos');
                
                // Limit to 5 photos
                if (count($uploadedPhotos) > 5) {
                    return back()->withInput()->with('error', 'You can only upload a maximum of 5 photos.');
                }

                foreach ($uploadedPhotos as $photo) {
                    $path = $photo->store('site-visit-requests', 'public');
                    $photos[] = $path;
                }
            }

            $validated['user_id'] = $user->id;
            $validated['photos'] = $photos;
            $validated['status'] = 'pending';

            $siteVisitRequest = SiteVisitRequest::create($validated);

            // Create notification for all admins
            $admins = User::where('role', 'admin')
                ->orWhere('role', 'super_admin')
                ->get();
            
            foreach ($admins as $admin) {
                Notification::create([
                    'user_id' => $admin->id,
                    'type' => 'site_visit_request',
                    'title' => 'New Site Visit Request',
                    'message' => $user->first_name . ' ' . $user->last_name . ' has submitted a new site visit request.',
                    'link' => route('site-visit-requests.index'),
                    'is_read' => false,
                ]);
            }

            return redirect()->route('client-data.index')
                ->with('success', 'Your site visit request has been submitted successfully. We will review it and get back to you soon.');

        } catch (\Exception $e) {
            Log::error('Error creating site visit request: ' . $e->getMessage());
            
            return back()->withInput()
                ->with('error', 'Error submitting request. Please try again.');
        }
    }

    /**
     * Approve a site visit request (admin only)
     */
    public function approveRequest(Request $request, SiteVisitRequest $siteVisitRequest)
    {
        if (!Auth::user()->hasAdminAccess()) {
            abort(403);
        }

        $validated = $request->validate([
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        // Update the request status
        $siteVisitRequest->update([
            'status' => 'approved',
            'admin_notes' => $validated['admin_notes'] ?? null,
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        // Automatically create a Site Visit from the approved request
        $siteVisit = SiteVisit::create([
            'user_id' => $siteVisitRequest->user_id, // Link to client account
            'location_address' => $siteVisitRequest->property_address,
            'client' => $siteVisitRequest->user ? $siteVisitRequest->user->first_name . ' ' . $siteVisitRequest->user->last_name : 'Guest',
            'contact_number' => $siteVisitRequest->user ? $siteVisitRequest->user->contact_number : null,
            'email' => $siteVisitRequest->user ? $siteVisitRequest->user->email : null,
            'visit_date' => $siteVisitRequest->preferred_date,
            'status' => 'pending', // Set to pending so it appears in Pending tab
            'client_data_open' => true, // Open for client uploads immediately
            'notes' => 'Created from Site Visit Request #' . $siteVisitRequest->id . "\n\n" . 
                       'Project Description: ' . $siteVisitRequest->project_description . "\n" .
                       'Property Size: ' . $siteVisitRequest->property_size . "\n" .
                       'Current Condition: ' . $siteVisitRequest->current_condition . "\n" .
                       'Special Requirements: ' . $siteVisitRequest->special_requirements,
        ]);

        // Link the site visit back to the request
        $siteVisitRequest->update([
            'site_visit_id' => $siteVisit->id,
        ]);

        // Create notification for the client
        if ($siteVisitRequest->user_id) {
            Notification::create([
                'user_id' => $siteVisitRequest->user_id,
                'type' => 'site_visit_approved',
                'title' => 'Site Visit Request Approved',
                'message' => 'Your site visit request has been approved! You can now view the site data and upload your documents.',
                'link' => route('client-data.show', $siteVisit->id),
                'is_read' => false,
            ]);
        }

        return redirect()->route('site-visit-requests.index')
            ->with('success', 'Site visit request approved and site visit created successfully. The site visit is now in the Pending tab.');
    }

    /**
     * Reject a site visit request (admin only)
     */
    public function rejectRequest(Request $request, SiteVisitRequest $siteVisitRequest)
    {
        if (!Auth::user()->hasAdminAccess()) {
            abort(403);
        }

        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        $siteVisitRequest->update([
            'status' => 'rejected',
            'rejection_reason' => $validated['rejection_reason'],
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        return redirect()->route('site-visit-requests.index')
            ->with('success', 'Site visit request rejected. The client has been notified.');
    }

    /**
     * Delete a site visit request (admin only)
     */
    public function destroyRequest(SiteVisitRequest $siteVisitRequest)
    {
        if (!Auth::user()->hasAdminAccess()) {
            abort(403);
        }

        // Delete associated photos from storage
        if ($siteVisitRequest->photos && is_array($siteVisitRequest->photos)) {
            foreach ($siteVisitRequest->photos as $photo) {
                if (Storage::disk('public')->exists($photo)) {
                    Storage::disk('public')->delete($photo);
                }
            }
        }

        // Store client name for success message
        $clientName = $siteVisitRequest->user->first_name . ' ' . $siteVisitRequest->user->last_name;

        // IMPORTANT: Do NOT delete the associated site visit
        // Only unlink the request from the site visit
        if ($siteVisitRequest->site_visit_id) {
            $siteVisit = SiteVisit::find($siteVisitRequest->site_visit_id);
            if ($siteVisit) {
                // The site visit remains, we just delete the request record
                // This allows admins to clean up requests without affecting actual site visits
            }
        }

        // Delete the request
        $siteVisitRequest->delete();

        return redirect()->route('site-visit-requests.index')
            ->with('success', 'Site visit request from ' . $clientName . ' has been deleted successfully. The associated site visit (if any) was preserved.');
    }

    /**
     * Delete a site visit request (client can delete their own)
     */
    public function destroyClientRequest(SiteVisitRequest $siteVisitRequest)
    {
        $user = Auth::user();
        
        // Check if this request belongs to the user
        if ($siteVisitRequest->user_id !== $user->id) {
            abort(403, 'You can only delete your own requests.');
        }

        // Clients can only delete pending or rejected requests
        if ($siteVisitRequest->status === 'approved') {
            return redirect()->route('my-requests.index')
                ->with('error', 'You cannot delete an approved request. Please contact admin if you need assistance.');
        }

        // Delete associated photos from storage
        if ($siteVisitRequest->photos && is_array($siteVisitRequest->photos)) {
            foreach ($siteVisitRequest->photos as $photo) {
                if (Storage::disk('public')->exists($photo)) {
                    Storage::disk('public')->delete($photo);
                }
            }
        }

        // Delete the request
        $siteVisitRequest->delete();

        return redirect()->route('my-requests.index')
            ->with('success', 'Your site visit request has been deleted successfully.');
    }
}
