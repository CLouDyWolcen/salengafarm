<?php

namespace App\Http\Controllers;

use App\Models\DisplayPlant;
use App\Models\Plant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class PublicController extends Controller
{
    public function index()
    {
        $plants = DisplayPlant::all();
        $inventoryPlants = Plant::orderBy('name', 'asc')->get();
        
        // Filter out base categories that are already shown in the main category grid
        $baseCategoryNames = ['shrub', 'herbs', 'palm', 'tree', 'grass', 'bamboo', 'fertilizer'];
        $additionalCategories = \App\Models\Category::whereNotIn('name', $baseCategoryNames)->get();
        
        return view('public.plants', compact('plants', 'inventoryPlants', 'additionalCategories'));
    }

    public function update(Request $request, DisplayPlant $plant)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'code' => 'nullable|string|max:50',
                'scientific_name' => 'nullable|string|max:255',
                'category' => 'required|string',
                'description' => 'nullable|string',
                'height_mm' => 'nullable|numeric',
                'spread_mm' => 'nullable|numeric',
                'spacing_mm' => 'nullable|numeric',
            ]);

            $plant->update($validated);

            return response()->json([
                'message' => 'Display plant updated successfully',
                'plant' => $plant->fresh()
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error updating display plant: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error updating display plant',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'scientific_name' => 'nullable|string|max:255',
            'category' => 'required|string',
            'description' => 'nullable|string',
            'height_mm' => 'nullable|numeric',
            'spread_mm' => 'nullable|numeric',
            'spacing_mm' => 'nullable|numeric',
            'photo' => 'nullable|image|max:2048'
        ]);

        $plant = new DisplayPlant($validated);

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('display-plant-photos', 'public');
            $plant->photo_path = $path;
        }

        $plant->save();

        return response()->json(['message' => 'Plant added successfully']);
    }

    public function destroy(DisplayPlant $plant)
    {
        $plant->delete();
        return response()->json(['message' => 'Plant removed from display successfully']);
    }

    public function uploadPhoto(Request $request)
    {
        try {
            $request->validate([
                'plant_id' => 'required|exists:display_plants,id',
                'photo' => 'required|image|max:2048'
            ]);

            $plant = DisplayPlant::findOrFail($request->plant_id);

            if ($request->hasFile('photo')) {
                // Remove old photo if exists
                if ($plant->photo_path) {
                    Storage::disk('public')->delete($plant->photo_path);
                }

                $path = $request->file('photo')->store('display-plant-photos', 'public');
                $plant->update(['photo_path' => $path]);

                return response()->json([
                    'message' => 'Photo uploaded successfully',
                    // Return relative path for client consistency
                    'path' => $path
                ]);
            }

            return response()->json(['message' => 'No photo file received'], 400);

        } catch (\Exception $e) {
            Log::error('Display plant photo upload error: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error uploading photo: ' . $e->getMessage(),
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function removePhoto($plant)
    {
        try {
            $plant = DisplayPlant::findOrFail($plant);

            if ($plant->photo_path) {
                Storage::disk('public')->delete($plant->photo_path);
                $plant->update(['photo_path' => null]);
            }

            return response()->json(['message' => 'Photo removed successfully']);
        } catch (\Exception $e) {
            Log::error('Error removing display plant photo: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error removing photo',
                'error' => $e->getMessage()
            ], 500);
        }
    }
} 