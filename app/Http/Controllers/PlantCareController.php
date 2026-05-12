<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DisplayPlant;
use Illuminate\Support\Facades\Auth;

class PlantCareController extends Controller
{
    /**
     * Display the plant care library
     */
    public function index()
    {
        $user = Auth::user();
        
        // Check if user has page access permission for plant_guide (clients only, admins and guests can access)
        if ($user && !$user->hasAdminAccess() && !$user->hasPageAccess('plant_guide')) {
            abort(403, 'Access denied. You do not have permission to access the Plant Guide.');
        }
        
        $plants = DisplayPlant::orderBy('name')->get();
        
        return view('plant-care.index', compact('plants'));
    }
    
    /**
     * Admin Plant Care Management Page
     */
    public function adminIndex()
    {
        // Check if user has admin access
        if (!Auth::check() || !Auth::user()->hasAdminAccess()) {
            return redirect()->route('public.plants')->with('error', 'Unauthorized access.');
        }
        
        // Get all plants with care status
        $plants = DisplayPlant::orderBy('name')->get()->map(function ($plant) {
            // Check if plant has any care information
            $plant->has_care_info = !empty($plant->care_watering) || 
                                   !empty($plant->care_sunlight) || 
                                   !empty($plant->care_soil) ||
                                   !empty($plant->care_temperature) ||
                                   !empty($plant->care_humidity) ||
                                   !empty($plant->care_fertilizing) ||
                                   !empty($plant->care_pruning) ||
                                   !empty($plant->care_propagation) ||
                                   !empty($plant->care_pests) ||
                                   !empty($plant->care_growth_rate) ||
                                   !empty($plant->care_toxicity) ||
                                   !empty($plant->care_notes);
            return $plant;
        });
        
        return view('plant-care.admin-index', compact('plants'));
    }
    
    /**
     * Show care details for a specific plant
     */
    public function show($id)
    {
        $plant = DisplayPlant::findOrFail($id);
        
        return view('plant-care.show', compact('plant'));
    }
    
    /**
     * Show the form for editing care information (Admin only)
     */
    public function edit($id)
    {
        // Check if user has admin access
        if (!Auth::check() || !Auth::user()->hasAdminAccess()) {
            abort(403, 'Unauthorized action.');
        }
        
        $plant = DisplayPlant::findOrFail($id);
        
        return view('plant-care.edit', compact('plant'));
    }
    
    /**
     * Update care information (Admin only)
     */
    public function update(Request $request, $id)
    {
        // Check if user has admin access
        if (!Auth::check() || !Auth::user()->hasAdminAccess()) {
            abort(403, 'Unauthorized action.');
        }
        
        $plant = DisplayPlant::findOrFail($id);
        
        $validated = $request->validate([
            'care_watering' => 'nullable|string',
            'care_sunlight' => 'nullable|string',
            'care_soil' => 'nullable|string',
            'care_temperature' => 'nullable|string',
            'care_humidity' => 'nullable|string',
            'care_fertilizing' => 'nullable|string',
            'care_pruning' => 'nullable|string',
            'care_propagation' => 'nullable|string',
            'care_pests' => 'nullable|string',
            'care_growth_rate' => 'nullable|string',
            'care_toxicity' => 'nullable|string',
            'care_notes' => 'nullable|string',
        ]);
        
        $plant->update($validated);
        
        // Check if came from admin page
        $redirectRoute = $request->input('from') === 'admin' ? 'plant-care.admin' : 'plant-care.show';
        $redirectId = $redirectRoute === 'plant-care.show' ? $plant->id : null;
        
        return $redirectId 
            ? redirect()->route($redirectRoute, $redirectId)->with('success', 'Care information updated successfully!')
            : redirect()->route($redirectRoute)->with('success', 'Care information updated successfully!');
    }
}
