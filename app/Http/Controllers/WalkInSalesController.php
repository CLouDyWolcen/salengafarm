<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Plant;
use App\Models\Sale;
use App\Services\AuditService;

class WalkInSalesController extends Controller
{
    /**
     * Display the walk-in sales interface.
     */
    public function index()
    {
        // Get all available plants for selection (removed is_active condition)
        $plants = Plant::orderBy('name')
                     ->get();
        
        return view('walk-in.index', compact('plants'));
    }
    
    /**
     * Display sales records page for Super Admin
     */
    public function showRecords(Request $request)
    {
        $query = Sale::with('plant')
                    ->orderBy('created_at', 'desc');
                    
        // Apply date range filter if provided
        if ($request->has('start_date') && $request->start_date) {
            $query->where('sale_date', '>=', $request->input('start_date'));
        }
        
        if ($request->has('end_date') && $request->end_date) {
            $query->where('sale_date', '<=', $request->input('end_date') . ' 23:59:59');
        }
        
        $sales = $query->paginate(50);
        
        // Calculate totals
        $totalRevenue = Sale::sum('total_price');
        $totalSales = Sale::count();
        $totalQuantity = Sale::sum('quantity');
        
        return view('walk-in.records', compact('sales', 'totalRevenue', 'totalSales', 'totalQuantity'));
    }
    
    /**
     * Process a new walk-in sale
     */
    public function processSale(Request $request)
    {
        try {
            DB::beginTransaction();
            
            // Extract sale data
            $items = $request->input('items', []);
            $customerName = $request->input('customer_name');
            $customerEmail = $request->input('customer_email');
            $paymentMethod = $request->input('payment_method', 'cash');
            $notes = $request->input('notes');
            
            if (empty($items)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No items in the sale'
                ], 400);
            }
            
            // Process each item in the sale
            foreach ($items as $item) {
                // Create sale record
                $sale = new Sale();
                $sale->plant_id = $item['plant_id'];
                $sale->quantity = $item['quantity'];
                $sale->price = $item['price'];
                $sale->total_price = $item['total'];
                
                // Save the custom physical attributes
                $sale->height = isset($item['height']) ? $item['height'] : null;
                $sale->spread = isset($item['spread']) ? $item['spread'] : null;
                $sale->spacing = isset($item['spacing']) ? $item['spacing'] : null;
                
                // Save any other custom attributes as JSON
                $customAttributes = [];
                foreach ($item as $key => $value) {
                    if (!in_array($key, ['plant_id', 'quantity', 'price', 'total', 'height', 'spread', 'spacing', 'name'])) {
                        $customAttributes[$key] = $value;
                    }
                }
                if (!empty($customAttributes)) {
                    $sale->custom_attributes = $customAttributes;
                }
                
                $sale->customer_name = $customerName;
                $sale->customer_email = $customerEmail;
                $sale->payment_method = $paymentMethod;
                $sale->notes = $notes;
                $sale->sale_date = now();
                $sale->save();
                
                // Audit log
                AuditService::logSaleCreated($sale->id, [
                    'plant_id' => $sale->plant_id,
                    'quantity' => $sale->quantity,
                    'total_price' => $sale->total_price,
                    'customer_name' => $sale->customer_name,
                ]);
                
                // Update plant stock
                $plant = Plant::find($item['plant_id']);
                if ($plant) {
                    $plant->quantity = max(0, $plant->quantity - $item['quantity']);
                    $plant->save();
                }
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Sale processed successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error processing walk-in sale: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to process sale: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get sales records with optional filtering
     */
    public function getSalesRecords(Request $request)
    {
        try {
            $query = Sale::with('plant')
                        ->orderBy('created_at', 'desc');
                        
            // Apply date range filter if provided
            if ($request->has('start_date')) {
                $query->where('sale_date', '>=', $request->input('start_date'));
            }
            
            if ($request->has('end_date')) {
                $query->where('sale_date', '<=', $request->input('end_date') . ' 23:59:59');
            }
            
            $sales = $query->paginate(20);
            
            return response()->json([
                'success' => true,
                'data' => $sales
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching sales records: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch sales records: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Calculate sales percentages by plant
     */
    public function getSalesPercentages()
    {
        try {
            // Get total quantity of all sales
            $totalSales = Sale::sum('quantity');
            
            if ($totalSales == 0) {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'totalSales' => 0,
                        'percentages' => []
                    ]
                ]);
            }
            
            // Get sales by plant with percentage calculation
            $salesByPlant = DB::table('sales')
                ->join('plants', 'sales.plant_id', '=', 'plants.id')
                ->select(
                    'plants.id',
                    'plants.name',
                    DB::raw('SUM(sales.quantity) as total_quantity'),
                    DB::raw('SUM(sales.total_price) as total_value'),
                    DB::raw('(SUM(sales.quantity) / ' . $totalSales . ' * 100) as percentage')
                )
                ->groupBy('plants.id', 'plants.name')
                ->orderBy('total_quantity', 'desc')
                ->get();
            
            return response()->json([
                'success' => true,
                'data' => [
                    'totalSales' => $totalSales,
                    'percentages' => $salesByPlant
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error calculating sales percentages: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to calculate sales percentages: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Bulk delete sales records
     */
    public function bulkDelete(Request $request)
    {
        try {
            $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'required|integer|exists:sales,id'
            ]);
            
            $ids = $request->input('ids');
            
            // Delete the sales records
            $deletedCount = Sale::whereIn('id', $ids)->delete();
            
            return response()->json([
                'success' => true,
                'message' => "Successfully deleted {$deletedCount} sales record(s).",
                'deleted_count' => $deletedCount
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error: ' . $e->getMessage(),
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error bulk deleting sales records: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete sales records: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export sales records to Excel or CSV
     */
    public function export(Request $request)
    {
        $format = $request->get('format', 'xlsx'); // xlsx or csv
        
        // Get sales with filters (same as showRecords)
        $query = Sale::with('plant')->orderBy('sale_date', 'desc');
        
        // Apply date range filter if provided
        if ($request->has('start_date') && $request->start_date) {
            $query->where('sale_date', '>=', $request->input('start_date'));
        }
        
        if ($request->has('end_date') && $request->end_date) {
            $query->where('sale_date', '<=', $request->input('end_date') . ' 23:59:59');
        }
        
        $sales = $query->get();
        
        // Prepare headers
        $headers = [
            'Sale Date',
            'Plant Code',
            'Plant Name',
            'Quantity',
            'Unit Price (₱)',
            'Total Price (₱)',
            'Height (mm)',
            'Spread (mm)',
            'Spacing (mm)',
            'Customer Name',
            'Customer Email',
            'Payment Method',
            'Notes'
        ];
        
        // Prepare data rows
        $data = [];
        foreach ($sales as $sale) {
            $data[] = [
                $sale->sale_date ? $sale->sale_date->format('Y-m-d H:i:s') : '',
                $sale->plant->code ?? 'N/A',
                $sale->plant->name ?? 'Unknown Plant',
                $sale->quantity,
                number_format($sale->price, 2),
                number_format($sale->total_price, 2),
                $sale->height ?? '',
                $sale->spread ?? '',
                $sale->spacing ?? '',
                $sale->customer_name ?? '',
                $sale->customer_email ?? '',
                $sale->payment_method ?? '',
                $sale->notes ?? ''
            ];
        }
        
        // Add summary row at the end
        $totalQuantity = $sales->sum('quantity');
        $totalRevenue = $sales->sum('total_price');
        
        $data[] = []; // Empty row
        $data[] = [
            'TOTAL',
            '',
            '',
            $totalQuantity,
            '',
            number_format($totalRevenue, 2),
            '',
            '',
            '',
            '',
            '',
            '',
            ''
        ];
        
        // Use ExportService to generate file
        $exportService = new \App\Services\ExportService();
        return $exportService->export(
            $data,
            $headers,
            'sales_export',
            $format,
            'Walk-in Sales'
        );
    }
} 