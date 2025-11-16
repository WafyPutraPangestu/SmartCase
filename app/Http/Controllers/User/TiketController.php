<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\KategoriGangguan;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TiketController extends Controller
{
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
        $request->validate([
            'kategori_gangguan_id' => 'nullable|exists:kategori_gangguans,id',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'prioritas' => 'nullable|in:Rendah,Sedang,Tinggi',
        ]);

        Ticket::create([
            'user_id' => Auth::id(),
            'kategori_gangguan_id' => $request->kategori_gangguan_id,
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'prioritas' => $request->prioritas,
            'status' => 'Menunggu',
        ]);

        return redirect()->route('tiket.index')->with('success', 'Tiket berhasil dibuat.');
    }

    public function show($id)
    {
        $ticket = Ticket::where('id', $id)
            ->where('user_id', Auth::id())
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
            'kategori_gangguan_id' => 'nullable|exists:kategori_gangguans,id',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'prioritas' => 'nullable|in:Rendah,Sedang,Tinggi',
        ]);

        $ticket->update([
            'kategori_gangguan_id' => $request->kategori_gangguan_id,
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'prioritas' => $request->prioritas,
        ]);

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
