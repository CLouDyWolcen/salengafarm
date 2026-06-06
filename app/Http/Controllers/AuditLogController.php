<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AuditLogController extends Controller
{
    /**
     * Fetch audit logs via AJAX for modal
     */
    public function fetchLogs(Request $request)
    {
        // Ensure only super admin can access
        if (Auth::user()->role !== 'super_admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Get filter parameters
        $dateRange = $request->get('date_range', 'last_7_days');
        $actionType = $request->get('action_type');
        $search = $request->get('search');

        // Build query
        $query = AuditLog::with('user');

        // Apply date filter
        switch ($dateRange) {
            case 'today':
                $query->whereDate('created_at', Carbon::today());
                break;
            case 'last_7_days':
                $query->where('created_at', '>=', Carbon::now()->subDays(7));
                break;
            case 'last_30_days':
                $query->where('created_at', '>=', Carbon::now()->subDays(30));
                break;
        }

        // Apply action type filter
        if ($actionType) {
            $query->where('action', 'like', '%' . $actionType . '%');
        }

        // Apply search
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('user_email', 'like', '%' . $search . '%')
                    ->orWhere('action', 'like', '%' . $search . '%')
                    ->orWhere('ip_address', 'like', '%' . $search . '%');
            });
        }

        // Get logs (limit to 100 for modal)
        $logs = $query->orderBy('created_at', 'desc')->limit(100)->get();

        // Get statistics
        $stats = $this->getTodayStats();

        // Get unique action types
        $actionTypes = AuditLog::select('action')
            ->distinct()
            ->orderBy('action')
            ->pluck('action');

        return response()->json([
            'logs' => $logs,
            'stats' => $stats,
            'actionTypes' => $actionTypes,
        ]);
    }

    /**
     * Display audit logs (Super Admin only)
     */
    public function index(Request $request)
    {
        // Ensure only super admin can access
        if (Auth::user()->role !== 'super_admin') {
            abort(403, 'Unauthorized access');
        }

        // Get filter parameters
        $dateRange = $request->get('date_range', 'last_7_days');
        $userId = $request->get('user_id');
        $actionType = $request->get('action_type');
        $entityType = $request->get('entity_type');
        $search = $request->get('search');

        // Build query
        $query = AuditLog::with('user');

        // Apply date filter
        switch ($dateRange) {
            case 'today':
                $query->whereDate('created_at', Carbon::today());
                break;
            case 'last_7_days':
                $query->where('created_at', '>=', Carbon::now()->subDays(7));
                break;
            case 'last_30_days':
                $query->where('created_at', '>=', Carbon::now()->subDays(30));
                break;
            case 'custom':
                if ($request->has('start_date') && $request->has('end_date')) {
                    $query->whereBetween('created_at', [
                        $request->get('start_date'),
                        $request->get('end_date')
                    ]);
                }
                break;
        }

        // Apply user filter
        if ($userId) {
            $query->where('user_id', $userId);
        }

        // Apply action type filter
        if ($actionType) {
            $query->where('action', 'like', '%' . $actionType . '%');
        }

        // Apply entity type filter
        if ($entityType) {
            $query->where('entity_type', $entityType);
        }

        // Apply search
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('user_email', 'like', '%' . $search . '%')
                    ->orWhere('action', 'like', '%' . $search . '%')
                    ->orWhere('ip_address', 'like', '%' . $search . '%');
            });
        }

        // Get paginated results (newest first)
        $logs = $query->orderBy('created_at', 'desc')->paginate(50);

        // Get statistics for today
        $stats = $this->getTodayStats();

        // Get all users for filter dropdown
        $users = User::orderBy('first_name')
            ->get(['id', 'first_name', 'last_name', 'email'])
            ->map(function($user) {
                $user->name = $user->first_name . ' ' . $user->last_name;
                return $user;
            });

        // Get unique action types for filter
        $actionTypes = AuditLog::select('action')
            ->distinct()
            ->orderBy('action')
            ->pluck('action');

        // Get unique entity types for filter
        $entityTypes = AuditLog::select('entity_type')
            ->distinct()
            ->whereNotNull('entity_type')
            ->orderBy('entity_type')
            ->pluck('entity_type');

        return view('admin.audit-logs.index', compact(
            'logs',
            'stats',
            'users',
            'actionTypes',
            'entityTypes',
            'dateRange',
            'userId',
            'actionType',
            'entityType',
            'search'
        ));
    }

    /**
     * Show detailed view of a single audit log
     */
    public function show($id)
    {
        // Ensure only super admin can access
        if (Auth::user()->role !== 'super_admin') {
            abort(403, 'Unauthorized access');
        }

        $log = AuditLog::with('user')->findOrFail($id);

        return response()->json([
            'log' => $log,
            'changes_description' => $log->getChangesDescription(),
        ]);
    }

    /**
     * Export audit logs to CSV or Excel
     */
    public function export(Request $request)
    {
        // Ensure only super admin can access
        if (Auth::user()->role !== 'super_admin') {
            abort(403, 'Unauthorized access');
        }

        // Get format (default to csv for backward compatibility)
        $format = $request->get('format', 'csv');

        // Apply same filters as index
        $dateRange = $request->get('date_range', 'last_7_days');
        $userId = $request->get('user_id');
        $actionType = $request->get('action_type');
        $entityType = $request->get('entity_type');
        $search = $request->get('search');

        // Build query (same as index)
        $query = AuditLog::with('user');

        // Apply filters (same logic as index)
        switch ($dateRange) {
            case 'today':
                $query->whereDate('created_at', Carbon::today());
                break;
            case 'last_7_days':
                $query->where('created_at', '>=', Carbon::now()->subDays(7));
                break;
            case 'last_30_days':
                $query->where('created_at', '>=', Carbon::now()->subDays(30));
                break;
            case 'custom':
                if ($request->has('start_date') && $request->has('end_date')) {
                    $query->whereBetween('created_at', [
                        $request->get('start_date'),
                        $request->get('end_date')
                    ]);
                }
                break;
        }

        if ($userId) {
            $query->where('user_id', $userId);
        }

        if ($actionType) {
            $query->where('action', 'like', '%' . $actionType . '%');
        }

        if ($entityType) {
            $query->where('entity_type', $entityType);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('user_email', 'like', '%' . $search . '%')
                    ->orWhere('action', 'like', '%' . $search . '%')
                    ->orWhere('ip_address', 'like', '%' . $search . '%');
            });
        }

        // Get all logs (no pagination for export)
        $logs = $query->orderBy('created_at', 'desc')->get();

        // Prepare data for export
        $headers = [
            'ID',
            'Timestamp',
            'User Email',
            'User Role',
            'Action',
            'Entity Type',
            'Entity ID',
            'IP Address',
            'Changes'
        ];

        $data = [];
        foreach ($logs as $log) {
            $data[] = [
                $log->id,
                $log->created_at->format('Y-m-d H:i:s'),
                $log->user_email,
                $log->user_role,
                $log->action,
                $log->entity_type ?? '',
                $log->entity_id ?? '',
                $log->ip_address,
                $log->getChangesDescription(),
            ];
        }

        // Use ExportService for both formats
        $exportService = app(\App\Services\ExportService::class);
        return $exportService->export(
            $data,
            $headers,
            'audit_logs',
            $format,
            'Audit Logs'
        );
    }

    /**
     * Get statistics for today
     */
    private function getTodayStats()
    {
        $today = Carbon::today();

        return [
            'total_logs' => AuditLog::whereDate('created_at', $today)->count(),
            'unique_users' => AuditLog::whereDate('created_at', $today)
                ->distinct('user_id')
                ->whereNotNull('user_id')
                ->count('user_id'),
            'failed_logins' => AuditLog::whereDate('created_at', $today)
                ->where('action', 'Login Failed')
                ->count(),
            'critical_actions' => AuditLog::whereDate('created_at', $today)
                ->where(function ($q) {
                    $q->where('action', 'like', '%Deleted%')
                        ->orWhere('action', 'Role Changed');
                })
                ->count(),
        ];
    }
}
