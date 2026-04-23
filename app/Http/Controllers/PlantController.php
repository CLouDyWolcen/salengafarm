<?php

namespace App\Http\Controllers;

use App\Models\Plant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PlantController extends Controller
{
    public function index()
    {
        $plants = Plant::orderBy('name', 'asc')->get();
        
        // Define base categories that are hardcoded in the main category row
        $baseCategories = ['shrub', 'herbs', 'palm', 'tree', 'grass', 'bamboo', 'fertilizer'];
        
        // Get only additional categories (exclude base categories from "Show More")
        $categories = \App\Models\Category::whereNotIn('slug', $baseCategories)->get();
        
        return view('plants.index', compact('plants', 'categories'));
    }

    public function store(Request $request)
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
                'photo' => 'nullable|image|max:2048',
                'oc' => 'nullable|string|max:50',
                'price' => 'nullable|numeric|min:0',
                'cost_per_sqm' => 'nullable|numeric|min:0',
                'pieces_per_sqm' => 'nullable|numeric|min:0',
                'cost_per_mm' => 'nullable|numeric|min:0',
                'quantity' => 'nullable|numeric|min:0'
            ]);

            $plant = new Plant($validated);

            if ($request->hasFile('photo')) {
                $path = $request->file('photo')->store('plant-photos', 'public');
                $plant->photo_path = $path;
            }

            $plant->save();

            Log::info('Plant created', ['id' => $plant->id, 'name' => $plant->name, 'category' => $plant->category]);

            return response()->json([
                'message' => 'Plant added successfully',
                'plant' => $plant
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error creating plant: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error creating plant',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, Plant $plant)
    {
        try {
            // Validate the request
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'code' => 'nullable|string|max:50',
                'scientific_name' => 'nullable|string|max:255',
                'description' => 'nullable|string',
                'category' => 'nullable|string|max:50',
                'height_mm' => 'nullable|numeric',
                'spread_mm' => 'nullable|numeric',
                'spacing_mm' => 'nullable|numeric',
                'oc' => 'nullable|string|max:50',
                'price' => 'nullable|numeric|min:0',
                'cost_per_sqm' => 'nullable|numeric|min:0',
                'pieces_per_sqm' => 'nullable|numeric|min:0',
                'cost_per_mm' => 'nullable|numeric|min:0',
                'quantity' => 'nullable|numeric|min:0'
            ]);

            $plant->update([
                'name' => $validated['name'],
                'code' => $validated['code'] ?? null,
                'scientific_name' => $validated['scientific_name'] ?? null,
                'description' => $validated['description'] ?? null,
                'category' => $validated['category'] ?? 'shrub',
                'height_mm' => $validated['height_mm'] ?? null,
                'spread_mm' => $validated['spread_mm'] ?? null,
                'spacing_mm' => $validated['spacing_mm'] ?? null,
                'oc' => $validated['oc'] ?? null,
                'price' => $validated['price'] ?? 0,
                'cost_per_sqm' => $validated['cost_per_sqm'] ?? 0,
                'pieces_per_sqm' => $validated['pieces_per_sqm'] ?? 0,
                'cost_per_mm' => $validated['cost_per_mm'] ?? 0,
                'quantity' => $validated['quantity'] ?? 0
            ]);

            return response()->json([
                'message' => 'Plant updated successfully',
                'plant' => $plant
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error updating plant: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error updating plant',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Plant $plant)
    {
        try {
            $plant->delete();
            return response()->json(['message' => 'Plant deleted successfully']);
        } catch (\Exception $e) {
            Log::error('Error deleting plant: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error deleting plant',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function bulkUpdate(Request $request)
    {
        try {
            $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'exists:plants,id',
                'availability' => 'nullable|in:in_stock,out_of_stock,pending',
                'price' => 'nullable|numeric|min:0',
                'cost_per_sqm' => 'nullable|numeric|min:0',
                'cost_per_mm' => 'nullable|numeric|min:0',
                'quantity' => 'nullable|numeric|min:0'
            ]);

            $updateData = [];
            
            // Only include fields that were provided
            if ($request->filled('availability')) {
                $updateData['quantity'] = $request->availability === 'in_stock' ? 1 : 0;
            }
            if ($request->filled('price')) {
                $updateData['price'] = $request->price;
            }
            if ($request->filled('cost_per_sqm')) {
                $updateData['cost_per_sqm'] = $request->cost_per_sqm;
            }
            if ($request->filled('cost_per_mm')) {
                $updateData['cost_per_mm'] = $request->cost_per_mm;
            }
            if ($request->filled('quantity')) {
                $updateData['quantity'] = $request->quantity;
            }

            // Update all selected plants
            Plant::whereIn('id', $request->ids)->update($updateData);

            return response()->json([
                'message' => 'Plants updated successfully'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error bulk updating plants: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error updating plants',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function uploadPhoto(Request $request)
    {
        try {
            Log::info('Upload photo request received', [
                'request_data' => $request->all(),
                'has_file' => $request->hasFile('photo'),
                'files' => $request->allFiles()
            ]);

            $request->validate([
                'plant_id' => 'required|exists:plants,id',
                'photo' => 'required|image|max:2048'
            ]);

            $plant = Plant::findOrFail($request->plant_id);
            Log::info('Plant found', ['plant' => $plant->toArray()]);

            if ($request->hasFile('photo')) {
                try {
                    // Delete old photo if exists
                    if ($plant->photo_path) {
                        Storage::disk('public')->delete($plant->photo_path);
                    }

                    $file = $request->file('photo');
                    Log::info('Photo file info', [
                        'original_name' => $file->getClientOriginalName(),
                        'mime_type' => $file->getMimeType(),
                        'size' => $file->getSize()
                    ]);

                    $path = $file->store('plant-photos', 'public');
                    Log::info('Photo stored', ['path' => $path]);

                    $plant->update(['photo_path' => $path]);
                    Log::info('Plant updated with new photo path');

                    return response()->json([
                        'message' => 'Photo uploaded successfully',
                        'path' => $path
                    ]);
                } catch (\Exception $e) {
                    Log::error('Storage error: ' . $e->getMessage(), [
                        'exception' => $e,
                        'trace' => $e->getTraceAsString()
                    ]);
                    throw $e;
                }
            }

            Log::warning('No photo file in request');
            return response()->json([
                'message' => 'No photo file received'
            ], 400);

        } catch (\Exception $e) {
            Log::error('Photo upload error: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'message' => 'Error uploading photo: ' . $e->getMessage(),
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function removePhoto($plant)
    {
        $plant = Plant::findOrFail($plant);

        if ($plant->photo_path) {
            Storage::disk('public')->delete($plant->photo_path);
            $plant->update(['photo_path' => null]);
        }

        return response()->json(['message' => 'Photo removed successfully']);
    }

    /**
     * Search plants from inventory
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $query = $request->input('search');
        $exclude = $request->input('exclude');
        
        // Start building the query
        $plantsQuery = Plant::where(function($q) use ($query) {
            $q->where('name', 'like', "%{$query}%")
              ->orWhere('scientific_name', 'like', "%{$query}%")
              ->orWhere('code', 'like', "%{$query}%");
        });
        
        // If exclude parameter is provided, exclude those plants
        if ($exclude) {
            try {
                $excludedPlants = json_decode($exclude, true);
                if (is_array($excludedPlants) && !empty($excludedPlants)) {
                    $plantsQuery->whereNotIn('name', $excludedPlants);
                }
            } catch (\Exception $e) {
                Log::error('Error parsing excluded plants: ' . $e->getMessage());
            }
        }
        
        $plants = $plantsQuery->limit(10)->get();
        
        return response()->json($plants);
    }
}
