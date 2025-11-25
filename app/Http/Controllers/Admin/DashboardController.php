<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Ticket;
use App\Models\User;
use App\Models\KategoriGangguan;
use App\Models\KategoriPelanggan;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Main Dashboard View
     */
    public function index()
    {
        return view('admin.dashboard.index');
    }

    /**
     * API: Get Dashboard Statistics
     * GET /api/admin/stats
     */
    public function getStats(Request $request)
    {
        try {
            $period = $request->get('period', '7'); // 7, 30, 90 days
            $startDate = Carbon::now()->subDays((int)$period);

            // Total Statistics
            $totalTickets = Ticket::count();
            $totalUsers = User::where('role', 'user')->count();
            $totalGangguan = KategoriGangguan::count();
            $totalPelanggan = KategoriPelanggan::count();

            // Status Statistics
            $statusStats = Ticket::select('status', DB::raw('count(*) as total'))
                ->groupBy('status')
                ->get()
                ->pluck('total', 'status');

            // Priority Statistics
            $priorityStats = Ticket::select('prioritas', DB::raw('count(*) as total'))
                ->whereNotNull('prioritas')
                ->groupBy('prioritas')
                ->get()
                ->pluck('total', 'prioritas');

            // Trend Data (Daily tickets in period)
            $trendData = Ticket::select(
                    DB::raw('DATE(created_at) as date'),
                    DB::raw('count(*) as total')
                )
                ->where('created_at', '>=', $startDate)
                ->groupBy('date')
                ->orderBy('date')
                ->get();

            // Recent Tickets
            $recentTickets = Ticket::with(['user', 'kategoriGangguan'])
                ->latest()
                ->limit(10)
                ->get();

            // ML Statistics
            $mlStats = [
                'total_predicted' => Ticket::whereNotNull('ml_predicted_at')->count(),
                'avg_confidence' => round(Ticket::whereNotNull('ml_confidence')->avg('ml_confidence') ?? 0, 4),
                'high_confidence' => Ticket::where('ml_confidence', '>=', 0.70)->count(),
                'low_confidence' => Ticket::where('ml_confidence', '<', 0.70)
                    ->whereNotNull('ml_confidence')
                    ->count(),
            ];

            // Top Gangguan Categories
            $topGangguan = Ticket::select('kategori_gangguan_nama', DB::raw('count(*) as total'))
                ->whereNotNull('kategori_gangguan_nama')
                ->groupBy('kategori_gangguan_nama')
                ->orderByDesc('total')
                ->limit(5)
                ->get();

            // Recent Activity (Last 24h)
            $recentActivity = [
                'new_tickets' => Ticket::where('created_at', '>=', Carbon::now()->subDay())->count(),
                'resolved_tickets' => Ticket::where('status', 'Selesai')
                    ->where('updated_at', '>=', Carbon::now()->subDay())
                    ->count(),
                'new_users' => User::where('created_at', '>=', Carbon::now()->subDay())->count(),
            ];

            // Response Time Average (for completed tickets)
            $avgResponseTime = Ticket::where('status', 'Selesai')
                ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, created_at, updated_at)) as avg_hours')
                ->value('avg_hours');

            return response()->json([
                'success' => true,
                'data' => [
                    'totals' => [
                        'tickets' => $totalTickets,
                        'users' => $totalUsers,
                        'gangguan' => $totalGangguan,
                        'pelanggan' => $totalPelanggan,
                    ],
                    'status' => $statusStats,
                    'priority' => $priorityStats,
                    'trend' => $trendData,
                    'recent_tickets' => $recentTickets,
                    'ml_stats' => $mlStats,
                    'top_gangguan' => $topGangguan,
                    'recent_activity' => $recentActivity,
                    'avg_response_time' => round($avgResponseTime ?? 0, 1),
                    'period' => $period,
                ],
                'timestamp' => now()->toISOString(),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch dashboard stats',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * API: Get Ticket List with Filters
     * GET /api/admin/tickets
     */
    public function getTickets(Request $request)
    {
        try {
            $query = Ticket::with(['user', 'kategoriGangguan']);

            // Filters
            if ($request->has('status') && $request->status !== 'all') {
                $query->where('status', $request->status);
            }

            if ($request->has('prioritas') && $request->prioritas !== 'all') {
                $query->where('prioritas', $request->prioritas);
            }

            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('kode_tiket', 'like', "%{$search}%")
                      ->orWhere('judul', 'like', "%{$search}%")
                      ->orWhere('deskripsi', 'like', "%{$search}%");
                });
            }

            // Sorting
            $sortBy = $request->get('sort_by', 'created_at');
            $sortOrder = $request->get('sort_order', 'desc');
            $query->orderBy($sortBy, $sortOrder);

            // Pagination
            $perPage = $request->get('per_page', 15);
            $tickets = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $tickets,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch tickets',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * API: Update Ticket Status
     * POST /api/admin/tickets/{id}/status
     */
    public function updateTicketStatus(Request $request, $id)
    {
        try {
            $request->validate([
                'status' => 'required|in:Menunggu,Diproses,Selesai',
            ]);

            $ticket = Ticket::findOrFail($id);
            $oldStatus = $ticket->status;
            $ticket->status = $request->status;
            $ticket->save();

            return response()->json([
                'success' => true,
                'message' => 'Status tiket berhasil diubah',
                'data' => [
                    'ticket' => $ticket->fresh(['user', 'kategoriGangguan']),
                    'old_status' => $oldStatus,
                    'new_status' => $ticket->status,
                ],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah status tiket',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * API: Update Ticket Priority
     * POST /api/admin/tickets/{id}/priority
     */
    public function updateTicketPriority(Request $request, $id)
    {
        try {
            $request->validate([
                'prioritas' => 'required|in:Rendah,Sedang,Tinggi',
            ]);

            $ticket = Ticket::findOrFail($id);
            $oldPriority = $ticket->prioritas;
            $ticket->prioritas = $request->prioritas;
            $ticket->save();

            return response()->json([
                'success' => true,
                'message' => 'Prioritas tiket berhasil diubah',
                'data' => [
                    'ticket' => $ticket->fresh(['user', 'kategoriGangguan']),
                    'old_priority' => $oldPriority,
                    'new_priority' => $ticket->prioritas,
                ],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah prioritas tiket',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * API: Bulk Update Ticket Status
     * POST /api/admin/tickets/bulk-status
     */
    public function bulkUpdateStatus(Request $request)
    {
        try {
            $request->validate([
                'ticket_ids' => 'required|array',
                'ticket_ids.*' => 'exists:tickets,id',
                'status' => 'required|in:Menunggu,Diproses,Selesai',
            ]);

            $updated = Ticket::whereIn('id', $request->ticket_ids)
                ->update(['status' => $request->status]);

            return response()->json([
                'success' => true,
                'message' => "{$updated} tiket berhasil diubah statusnya",
                'data' => [
                    'updated_count' => $updated,
                    'status' => $request->status,
                ],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah status tiket',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * API: Get User List
     * GET /api/admin/users
     */
    public function getUsers(Request $request)
    {
        try {
            $query = User::with('profile.kategoriPelanggan');

            if ($request->has('role') && $request->role !== 'all') {
                $query->where('role', $request->role);
            }

            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            }

            $perPage = $request->get('per_page', 15);
            $users = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $users,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch users',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * API: Get Charts Data
     * GET /api/admin/charts
     */
    public function getChartsData(Request $request)
    {
        try {
            $days = $request->get('days', 30);
            $startDate = Carbon::now()->subDays($days);

            // Tickets per day
            $ticketsPerDay = Ticket::select(
                    DB::raw('DATE(created_at) as date'),
                    DB::raw('count(*) as total')
                )
                ->where('created_at', '>=', $startDate)
                ->groupBy('date')
                ->orderBy('date')
                ->get();

            // Priority distribution over time
            $priorityTrend = Ticket::select(
                    DB::raw('DATE(created_at) as date'),
                    'prioritas',
                    DB::raw('count(*) as total')
                )
                ->where('created_at', '>=', $startDate)
                ->whereNotNull('prioritas')
                ->groupBy('date', 'prioritas')
                ->orderBy('date')
                ->get()
                ->groupBy('date');

            // Status distribution over time
            $statusTrend = Ticket::select(
                    DB::raw('DATE(created_at) as date'),
                    'status',
                    DB::raw('count(*) as total')
                )
                ->where('created_at', '>=', $startDate)
                ->groupBy('date', 'status')
                ->orderBy('date')
                ->get()
                ->groupBy('date');

            // Category distribution
            $categoryDistribution = Ticket::select(
                    'kategori_gangguan_nama',
                    DB::raw('count(*) as total')
                )
                ->whereNotNull('kategori_gangguan_nama')
                ->groupBy('kategori_gangguan_nama')
                ->orderByDesc('total')
                ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'tickets_per_day' => $ticketsPerDay,
                    'priority_trend' => $priorityTrend,
                    'status_trend' => $statusTrend,
                    'category_distribution' => $categoryDistribution,
                ],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch chart data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * API: Export Data
     * GET /api/admin/export
     */
    public function exportData(Request $request)
    {
        try {
            $type = $request->get('type', 'tickets'); // tickets, users
            $format = $request->get('format', 'json'); // json, csv

            if ($type === 'tickets') {
                $data = Ticket::with(['user', 'kategoriGangguan'])->get();
            } else {
                $data = User::with('profile.kategoriPelanggan')->get();
            }

            if ($format === 'csv') {
                // Generate CSV
                $filename = "{$type}_" . now()->format('Y-m-d_His') . ".csv";
                
                $headers = [
                    'Content-Type' => 'text/csv',
                    'Content-Disposition' => "attachment; filename=\"{$filename}\"",
                ];

                $callback = function() use ($data, $type) {
                    $file = fopen('php://output', 'w');
                    
                    if ($type === 'tickets') {
                        fputcsv($file, ['ID', 'Kode Tiket', 'User', 'Judul', 'Status', 'Prioritas', 'Created At']);
                        foreach ($data as $row) {
                            fputcsv($file, [
                                $row->id,
                                $row->kode_tiket,
                                $row->user->name ?? '-',
                                $row->judul,
                                $row->status,
                                $row->prioritas ?? '-',
                                $row->created_at,
                            ]);
                        }
                    } else {
                        fputcsv($file, ['ID', 'Name', 'Email', 'Role', 'Created At']);
                        foreach ($data as $row) {
                            fputcsv($file, [
                                $row->id,
                                $row->name,
                                $row->email,
                                $row->role,
                                $row->created_at,
                            ]);
                        }
                    }
                    
                    fclose($file);
                };

                return response()->stream($callback, 200, $headers);
            }

            return response()->json([
                'success' => true,
                'data' => $data,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to export data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}