<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $query = Ticket::where('user_id', Auth::id());
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('prioritas')) {
            $query->where('prioritas', $request->prioritas);
        }
        if ($request->filled('kategori')) {
            $query->where('kategori_gangguan_nama', $request->kategori);
        }
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('kode_tiket', 'like', '%' . $request->search . '%')
                  ->orWhere('judul', 'like', '%' . $request->search . '%')
                  ->orWhere('deskripsi', 'like', '%' . $request->search . '%');
            });
        }
        $sortBy = $request->get('sort', 'latest');
        switch ($sortBy) {
            case 'oldest':
                $query->oldest();
                break;
            case 'priority':
                $query->orderByRaw("FIELD(prioritas, 'Tinggi', 'Sedang', 'Rendah')");
                break;
            case 'status':
                $query->orderByRaw("FIELD(status, 'Diproses', 'Menunggu', 'Selesai')");
                break;
            default:
                $query->latest();
        }
        $tickets = $query->paginate(9)->withQueryString();
        $stats = [
            'total' => Ticket::where('user_id', Auth::id())->count(),
            'menunggu' => Ticket::where('user_id', Auth::id())->where('status', 'Menunggu')->count(),
            'diproses' => Ticket::where('user_id', Auth::id())->where('status', 'Diproses')->count(),
            'selesai' => Ticket::where('user_id', Auth::id())->where('status', 'Selesai')->count(),
            'tinggi' => Ticket::where('user_id', Auth::id())->where('prioritas', 'Tinggi')->count(),
        ];
        $categories = Ticket::where('user_id', Auth::id())
            ->whereNotNull('kategori_gangguan_nama')
            ->distinct()
            ->pluck('kategori_gangguan_nama');
        $aiInsights = [
            'total_predicted' => Ticket::where('user_id', Auth::id())->whereNotNull('ml_predicted_at')->count(),
            'avg_confidence' => Ticket::where('user_id', Auth::id())->whereNotNull('ml_confidence')->avg('ml_confidence'),
            'high_priority_count' => Ticket::where('user_id', Auth::id())->where('prioritas', 'Tinggi')->whereNotNull('ml_predicted_at')->count(),
        ];
        return view('user.dashboard.index', compact('tickets', 'stats', 'categories', 'aiInsights'));
    }
}