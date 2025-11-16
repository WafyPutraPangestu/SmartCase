<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;

class TiketController extends Controller
{
    public function index()
    {
        $tiket = Ticket::with(['user','kategoriGangguan'])->latest()->get();
        return view('admin.tiket.index', compact('tiket'));
    }

    public function show($id)
    {
        $tiket = Ticket::with(['user','kategoriGangguan'])->findOrFail($id);
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

    // API untuk Alpine.js
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
}
