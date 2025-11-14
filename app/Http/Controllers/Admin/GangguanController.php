<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KategoriGangguan;
use Illuminate\Http\Request;

class GangguanController extends Controller
{
    public function index()
    {
        $kategori = KategoriGangguan::all();
        return view('admin.kategori-gangguan.index', compact('kategori'));
    }

    public function create()
    {
        return view('admin.kategori-gangguan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_gangguan' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        KategoriGangguan::create($request->all());

        return redirect()->route('kategori_gangguan.index')
                         ->with('success', 'Kategori berhasil dibuat');
    }

    public function show(KategoriGangguan $kategoriGangguan)
    {
        return view('admin.kategori-gangguan.show', compact('kategoriGangguan'));
    }

    public function edit(KategoriGangguan $kategoriGangguan)
    {
        return view('admin.kategori-gangguan.edit', compact('kategoriGangguan'));
    }

    public function update(Request $request, KategoriGangguan $kategoriGangguan)
    {
        $request->validate([
            'nama_gangguan' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $kategoriGangguan->update($request->all());

        return redirect()->route('admin.kategori-gangguan.index')
                         ->with('success', 'Kategori berhasil diupdate');
    }

    public function destroy(KategoriGangguan $kategoriGangguan)
    {
        $kategoriGangguan->delete();
        return redirect()->route('admin.kategori-gangguan.index')
                         ->with('success', 'Kategori berhasil dihapus');
    }
}
