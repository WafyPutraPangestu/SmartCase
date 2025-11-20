<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\KategoriGangguan;
use App\Models\Ticket;
use App\Services\MLPredictionService;
use App\Services\MLRetrainingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TiketController extends Controller
{
    protected MLPredictionService $mlService;
    protected MLRetrainingService $retrainService;

    public function __construct(
        MLPredictionService $mlService,
        MLRetrainingService $retrainService
    ) {
        $this->mlService = $mlService;
        $this->retrainService = $retrainService;
    }

    public function index()
    {
        $tickets = Ticket::where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('user.tiket.index', compact('tickets'));
    }

    public function create()
    {
        $kategoriGangguan = KategoriGangguan::all();
        return view('user.tiket.create', compact('kategoriGangguan'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kategori_gangguan_id' => 'required|exists:kategori_gangguans,id',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
        ]);

        try {
            // ✨ Get ML prediction
            $prediction = $this->mlService->predictPriority($validated, Auth::user());

            // Create ticket dengan ML prediction
            $ticket = Ticket::create([
                'user_id' => Auth::id(),
                'kategori_gangguan_id' => $validated['kategori_gangguan_id'],
                'kategori_gangguan_nama' => $prediction['kategori_gangguan_nama'],
                'kategori_pelanggan_nama' => $prediction['kategori_pelanggan_nama'],
                'judul' => $validated['judul'],
                'deskripsi' => $validated['deskripsi'],
                'prioritas' => $prediction['prioritas'],
                'ml_confidence' => $prediction['confidence'],
                'ml_predicted_at' => $prediction['ml_predicted_at'],
                'ml_features' => $prediction['ml_features'] ?? null,
                'status' => 'Menunggu',
            ]);

            // ✨ Check auto-retrain (every 500 tickets)
            $this->retrainService->checkAndTriggerRetrain();

            // Flash message dengan info ML
            $message = "Tiket berhasil dibuat dengan prioritas: {$prediction['prioritas']} ";
            $message .= "(Confidence: " . round($prediction['confidence'] * 100, 1) . "%)";

            return redirect()->route('tiket.index')->with('success', $message);

        } catch (\Exception $e) {
            Log::error('ML Prediction failed in TiketController', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
            ]);

            // Fallback: create ticket tanpa ML (manual prioritas nanti oleh admin)
            $ticket = Ticket::create([
                'user_id' => Auth::id(),
                'kategori_gangguan_id' => $validated['kategori_gangguan_id'],
                'judul' => $validated['judul'],
                'deskripsi' => $validated['deskripsi'],
                'prioritas' => 'Sedang', // Default fallback
                'status' => 'Menunggu',
            ]);

            return redirect()->route('tiket.index')
                ->with('warning', 'Tiket berhasil dibuat. Prioritas akan ditentukan oleh admin.');
        }
    }

    public function show($id)
    {
        $ticket = Ticket::where('id', $id)
            ->where('user_id', Auth::id())
            ->with('kategoriGangguan')
            ->firstOrFail();

        return view('user.tiket.show', compact('ticket'));
    }

    public function edit($id)
    {
        $ticket = Ticket::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $kategoriGangguan = KategoriGangguan::all();

        return view('user.tiket.edit', compact('ticket', 'kategoriGangguan'));
    }

    public function update(Request $request, $id)
    {
        $ticket = Ticket::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $request->validate([
            'kategori_gangguan_id' => 'required|exists:kategori_gangguans,id',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
        ]);

        // Update ticket basic info
        $ticket->update([
            'kategori_gangguan_id' => $request->kategori_gangguan_id,
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
        ]);

        // ✨ Optional: Re-predict jika data berubah signifikan
        // (Uncomment jika mau auto re-predict saat edit)
        /*
        try {
            $prediction = $this->mlService->predictPriority([
                'kategori_gangguan_id' => $request->kategori_gangguan_id,
                'judul' => $request->judul,
                'deskripsi' => $request->deskripsi,
            ], Auth::user());

            $ticket->update([
                'prioritas' => $prediction['prioritas'],
                'ml_confidence' => $prediction['confidence'],
                'ml_predicted_at' => now(),
            ]);
        } catch (\Exception $e) {
            // Ignore ML error on update
        }
        */

        return redirect()->route('tiket.index')->with('success', 'Tiket berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $ticket = Ticket::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $ticket->delete();
        return redirect()->route('tiket.index')->with('success', 'Tiket berhasil dihapus.');
    }
}