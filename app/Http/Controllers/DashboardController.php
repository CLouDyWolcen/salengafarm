<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Plant;
use App\Models\Sale;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Get total stock
        $totalStock = Plant::sum('quantity');

        // Get low stock items (quantity less than 10)
        $lowStockItems = Plant::where('quantity', '<', 10)->get();

        // Get recent plants (last 5 added)
        $recentPlants = Plant::orderBy('created_at', 'desc')->take(5)->get();

        // Enhanced stock distribution by category with value
        $stockByCategory = Plant::selectRaw('category, sum(quantity) as total_quantity, sum(quantity * price) as total_value')
            ->groupBy('category')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->category => [
                    'quantity' => $item->total_quantity,
                    'value' => $item->total_value
                ]];
            })
            ->toArray();
            
        // Enhanced sales distribution with revenue data
        $salesByCategory = [];
        $totalSalesQuantity = Sale::sum('quantity');
        $totalSalesRevenue = DB::table('sales')
            ->join('plants', 'sales.plant_id', '=', 'plants.id')
            ->sum(DB::raw('sales.quantity * plants.price'));
        
        if ($totalSalesQuantity > 0) {
            $salesByCategory = DB::table('sales')
                ->join('plants', 'sales.plant_id', '=', 'plants.id')
                ->select(
                    'plants.category',
                    DB::raw('SUM(sales.quantity) as total_quantity'),
                    DB::raw('SUM(sales.quantity * plants.price) as total_revenue'),
                    DB::raw('(SUM(sales.quantity) / ' . $totalSalesQuantity . ' * 100) as quantity_percentage'),
                    DB::raw('(SUM(sales.quantity * plants.price) / ' . $totalSalesRevenue . ' * 100) as revenue_percentage')
                )
                ->groupBy('plants.category')
                ->orderBy('total_revenue', 'desc')
                ->get()
                ->mapWithKeys(function ($item) {
                    return [$item->category => [
                        'quantity' => $item->total_quantity,
                        'revenue' => $item->total_revenue,
                        'quantity_percentage' => $item->quantity_percentage,
                        'revenue_percentage' => $item->revenue_percentage
                    ]];
                })
                ->toArray();
        }

        // Sales trends over last 30 days
        $salesTrends = DB::table('sales')
            ->join('plants', 'sales.plant_id', '=', 'plants.id')
            ->select(
                DB::raw('DATE(sales.created_at) as sale_date'),
                DB::raw('SUM(sales.quantity) as daily_quantity'),
                DB::raw('SUM(sales.quantity * plants.price) as daily_revenue')
            )
            ->where('sales.created_at', '>=', now()->subDays(30))
            ->groupBy(DB::raw('DATE(sales.created_at)'))
            ->orderBy('sale_date')
            ->get()
            ->toArray();

        // Inventory turnover analysis
        $inventoryTurnover = [];
        foreach ($stockByCategory as $category => $data) {
            $categoryRevenue = $salesByCategory[$category]['revenue'] ?? 0;
            $categoryValue = $data['value'];
            $turnoverRate = $categoryValue > 0 ? ($categoryRevenue / $categoryValue) : 0;
            $inventoryTurnover[$category] = round($turnoverRate, 2);
        }

        // Top performing plants
        $topPlants = DB::table('sales')
            ->join('plants', 'sales.plant_id', '=', 'plants.id')
            ->select(
                'plants.name',
                'plants.category',
                DB::raw('SUM(sales.quantity) as total_sold'),
                DB::raw('SUM(sales.quantity * plants.price) as total_revenue')
            )
            ->groupBy('plants.id', 'plants.name', 'plants.category')
            ->orderBy('total_revenue', 'desc')
            ->take(5)
            ->get()
            ->toArray();

        // Get all plants for the update stock modal
        $plants = Plant::all();

        return view('dashboard', compact(
            'totalStock',
            'lowStockItems',
            'recentPlants',
            'stockByCategory',
            'salesByCategory',
            'salesTrends',
            'inventoryTurnover',
            'topPlants',
            'plants'
        ));
    }

    public function updateStock(Request $request)
    {
        $updates = $request->input('updates', []);

        foreach ($updates as $update) {
            if (isset($update['plant_id'], $update['quantity'])) {
                Plant::where('id', $update['plant_id'])
                    ->update(['quantity' => $update['quantity']]);
            }
        }

        return response()->json(['message' => 'Stock updated successfully']);
    }

    public function getAnalyticsData(Request $request)
    {
        $period = $request->get('period', '30'); // Default 30 days
        $type = $request->get('type', 'quantity'); // quantity or revenue
        
        // Sales trends for the specified period
        $salesTrends = DB::table('sales')
            ->join('plants', 'sales.plant_id', '=', 'plants.id')
            ->select(
                DB::raw('DATE(sales.created_at) as sale_date'),
                DB::raw('SUM(sales.quantity) as daily_quantity'),
                DB::raw('SUM(sales.quantity * plants.price) as daily_revenue')
            )
            ->where('sales.created_at', '>=', now()->subDays($period))
            ->groupBy(DB::raw('DATE(sales.created_at)'))
            ->orderBy('sale_date')
            ->get();

        // Enhanced sales by category for the period
        $totalSalesQuantity = Sale::where('created_at', '>=', now()->subDays($period))->sum('quantity');
        $totalSalesRevenue = DB::table('sales')
            ->join('plants', 'sales.plant_id', '=', 'plants.id')
            ->where('sales.created_at', '>=', now()->subDays($period))
            ->sum(DB::raw('sales.quantity * plants.price'));

        $salesByCategory = [];
        if ($totalSalesQuantity > 0) {
            $salesByCategory = DB::table('sales')
                ->join('plants', 'sales.plant_id', '=', 'plants.id')
                ->select(
                    'plants.category',
                    DB::raw('SUM(sales.quantity) as total_quantity'),
                    DB::raw('SUM(sales.quantity * plants.price) as total_revenue'),
                    DB::raw('(SUM(sales.quantity) / ' . $totalSalesQuantity . ' * 100) as quantity_percentage'),
                    DB::raw('(SUM(sales.quantity * plants.price) / ' . $totalSalesRevenue . ' * 100) as revenue_percentage')
                )
                ->where('sales.created_at', '>=', now()->subDays($period))
                ->groupBy('plants.category')
                ->orderBy('total_revenue', 'desc')
                ->get()
                ->mapWithKeys(function ($item) {
                    return [$item->category => [
                        'quantity' => $item->total_quantity,
                        'revenue' => $item->total_revenue,
                        'quantity_percentage' => $item->quantity_percentage,
                        'revenue_percentage' => $item->revenue_percentage
                    ]];
                })
                ->toArray();
        }

        return response()->json([
            'salesTrends' => $salesTrends,
            'salesByCategory' => $salesByCategory,
            'period' => $period,
            'type' => $type
        ]);
    }

    public function clientRequests()
    {
        // Assuming you have a Request model
        $requests = \App\Models\ClientRequest::latest()->paginate(10);
        
        return view('client-requests.index', compact('requests'));
    }
}