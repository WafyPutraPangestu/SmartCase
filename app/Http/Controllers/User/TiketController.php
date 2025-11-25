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
        $user = Auth::user();
        if (!$user->profile || is_null($user->profile->kategori_pelanggan_id)) {
            return redirect()->route('profile.index') 
                ->with('error', 'Mohon lengkapi Kategori Pelanggan di menu Profil sebelum membuat tiket.');
        }
        return view('user.tiket.create', compact('kategoriGangguan'));
    }

    public function store(Request $request)
{
    $user = Auth::user();
        if (!$user->profile || is_null($user->profile->kategori_pelanggan_id)) {
            return redirect()->route('profile.index')
                ->with('error', 'Gagal membuat tiket. Kategori Pelanggan belum diatur.');
        }

    $validated = $request->validate([
        'kategori_gangguan' => 'required|string|max:30',
        'judul' => 'required|string|max:255',
        'deskripsi' => 'required|string',
    ]);

    try {
        
        $kategoriInput = trim($request->kategori_gangguan);
        $kategoriNormalized = ucwords(strtolower($kategoriInput));

        
        $kategori = KategoriGangguan::firstOrCreate([
            'nama_gangguan' => $kategoriNormalized
        ]);
        $payloadML = [
            'kategori_gangguan' => $kategoriNormalized, 
            'judul' => $validated['judul'],
            'deskripsi' => $validated['deskripsi'],
        ];

        $prediction = $this->mlService->predictPriority($payloadML, Auth::user());

        if (!$prediction['success']) {
            throw new \Exception($prediction['message']);
        }

        
        $ticket = Ticket::create([
            'user_id' => Auth::id(),
            'kategori_gangguan_id' => $kategori->id,
            'kategori_gangguan_nama' => $prediction['kategori_gangguan_nama'],
            'kategori_pelanggan_nama' => $prediction['kategori_pelanggan_nama'],
            'judul' => $validated['judul'],
            'deskripsi' => $validated['deskripsi'],
            'prioritas' => $prediction['prioritas'],
            'ml_confidence' => $prediction['confidence'],
            'ml_predicted_at' => now(),
            'ml_features' => $prediction['ml_features'] ?? null,
            'status' => 'Menunggu',
        ]);

        
        $this->retrainService->checkAndTriggerRetrain();

        $message = "Tiket berhasil dibuat dengan prioritas: {$prediction['prioritas']} ";
        $message .= "(Confidence: " . round($prediction['confidence'] * 100, 1) . "%)";

        return redirect()->route('tiket.index')->with('success', $message);

    } catch (\Exception $e) {

        Log::error('ML Prediction failed in TiketController', [
            'error' => $e->getMessage(),
            'user_id' => Auth::id(),
        ]);

        return redirect()->back()
            ->withInput()
            ->with('error', 'Gagal membuat tiket karena layanan AI sedang offline. Silakan coba lagi nanti.');
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
    
        $validated = $request->validate([
            'kategori_gangguan' => 'required|string|max:30',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
        ]);
    
        try {
            
            $kategoriNormalized = ucwords(strtolower(trim($request->kategori_gangguan)));
    
            
            $kategori = KategoriGangguan::firstOrCreate([
                'nama_gangguan' => $kategoriNormalized
            ]);
    
            
            $payloadML = [
                'kategori_gangguan' => $kategoriNormalized,
                'judul' => $validated['judul'],
                'deskripsi' => $validated['deskripsi'],
            ];
    
            $prediction = $this->mlService->predictPriority($payloadML, Auth::user());
    
            if (!$prediction['success']) {
                throw new \Exception($prediction['message'] ?? 'ML prediction failed');
            }
    
            
            $updateData = [
                'kategori_gangguan_id' => $kategori->id,
                'judul' => $validated['judul'],
                'deskripsi' => $validated['deskripsi'],
                'prioritas' => $prediction['prioritas'],
                'ml_confidence' => $prediction['confidence'] ?? null,
                'ml_predicted_at' => now(),
            ];
    
            
            if (isset($prediction['kategori_gangguan_nama'])) {
                $updateData['kategori_gangguan_nama'] = $prediction['kategori_gangguan_nama'];
            }
            if (isset($prediction['kategori_pelanggan_nama'])) {
                $updateData['kategori_pelanggan_nama'] = $prediction['kategori_pelanggan_nama'];
            }
            if (isset($prediction['ml_features'])) {
                $updateData['ml_features'] = $prediction['ml_features'];
            }
    
            $ticket->update($updateData);
    
            $message = "Tiket berhasil diperbarui dengan prioritas: {$prediction['prioritas']}";
            if (isset($prediction['confidence'])) {
                $message .= " (Confidence: " . round($prediction['confidence'] * 100, 1) . "%)";
            }
    
            return redirect()->route('tiket.index')->with('success', $message);
    
        } catch (\Exception $e) {
    
            Log::error('ML Prediction failed in TiketController@update', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'ticket_id' => $id,
            ]);
    
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui tiket karena layanan AI sedang offline. Silakan coba lagi nanti.');
        }
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