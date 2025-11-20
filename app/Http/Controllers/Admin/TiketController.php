<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Services\MLRetrainingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TiketController extends Controller
{
    protected MLRetrainingService $retrainService;

    public function __construct(MLRetrainingService $retrainService)
    {
        $this->retrainService = $retrainService;
    }

    public function index()
    {
        $tiket = Ticket::with(['user', 'kategoriGangguan'])
            ->latest()
            ->get();
            
        return view('admin.tiket.index', compact('tiket'));
    }

    public function show($id)
    {
        $tiket = Ticket::with(['user', 'kategoriGangguan'])->findOrFail($id);
        return view('admin.tiket.show', compact('tiket'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Menunggu,Diproses,Selesai',
        ]);

        Ticket::findOrFail($id)->update([
            'status' => $request->status
        ]);

        return redirect()->back()->with('success', 'Status berhasil diperbarui');
    }

    // ✨ NEW: Update prioritas (manual override by admin)
    public function updatePrioritas(Request $request, $id)
    {
        $request->validate([
            'prioritas' => 'required|in:Rendah,Sedang,Tinggi',
        ]);

        $ticket = Ticket::findOrFail($id);
        $oldPrioritas = $ticket->prioritas;

        $ticket->update([
            'prioritas' => $request->prioritas
        ]);

        // Log for learning (optional: send feedback to ML)
        Log::info('Admin override prioritas', [
            'ticket_id' => $id,
            'old_prioritas' => $oldPrioritas,
            'new_prioritas' => $request->prioritas,
            'ml_confidence' => $ticket->ml_confidence,
            'admin_id' => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'Prioritas berhasil diperbarui');
    }

    // API untuk Alpine.js - Update Status
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Menunggu,Diproses,Selesai',
        ]);

        $tiket = Ticket::findOrFail($id);
        $tiket->update(['status' => $request->status]);

        return response()->json([
            'message' => 'Status diupdate',
            'status' => $tiket->status
        ]);
    }

    // ✨ NEW: API untuk Alpine.js - Update Prioritas
    public function updatePrioritasApi(Request $request, $id)
    {
        $request->validate([
            'prioritas' => 'required|in:Rendah,Sedang,Tinggi',
        ]);

        $tiket = Ticket::findOrFail($id);
        $oldPrioritas = $tiket->prioritas;
        
        $tiket->update(['prioritas' => $request->prioritas]);

        // Log untuk feedback ML
        Log::info('Admin override prioritas via API', [
            'ticket_id' => $id,
            'old' => $oldPrioritas,
            'new' => $request->prioritas,
        ]);

        return response()->json([
            'message' => 'Prioritas diupdate',
            'prioritas' => $tiket->prioritas
        ]);
    }

    // ✨ NEW: ML Dashboard untuk Admin
    public function mlDashboard()
    {
        $status = $this->retrainService->getStatus();
        
        // Get statistics
        $stats = [
            'total_tickets' => Ticket::count(),
            'tickets_with_ml' => Ticket::whereNotNull('ml_confidence')->count(),
            'avg_confidence' => Ticket::whereNotNull('ml_confidence')->avg('ml_confidence'),
            'high_confidence' => Ticket::where('ml_confidence', '>=', 0.70)->count(),
            'low_confidence' => Ticket::where('ml_confidence', '<', 0.70)->count(),
            'by_prioritas' => [
                'Tinggi' => Ticket::where('prioritas', 'Tinggi')->count(),
                'Sedang' => Ticket::where('prioritas', 'Sedang')->count(),
                'Rendah' => Ticket::where('prioritas', 'Rendah')->count(),
            ],
        ];
        
        return view('admin.ml.dashboard', compact('status', 'stats'));
    }

    // ✨ NEW: Manual Retrain (Admin action)
    public function manualRetrain()
    {
        try {
            $result = $this->retrainService->manualRetrain();
            
            return redirect()->back()->with('success', 'Model retraining dimulai!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memulai retraining: ' . $e->getMessage());
        }
    }
}