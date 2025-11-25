<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KategoriPelanggan;
use Illuminate\Http\Request;

class PelangganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kategoriPelanggans = KategoriPelanggan::latest()->paginate(10);
        return view('admin.kategori-pelanggan.index', compact('kategoriPelanggans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.kategori-pelanggan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategori_pelanggans,nama_kategori',
        ], [
            'nama_kategori.required' => 'Nama Kategori Pelanggan wajib diisi.',
            'nama_kategori.unique' => 'Nama Kategori Pelanggan sudah ada.',
        ]);

        KategoriPelanggan::create($validated);

        return redirect()->route('kategori_pelanggan.index')
            ->with('success', 'Kategori Pelanggan berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $kategori = KategoriPelanggan::findOrFail($id);
        return view('admin.kategori-pelanggan.show', compact('kategori'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $kategori = KategoriPelanggan::findOrFail($id);
        return view('admin.kategori-pelanggan.edit', compact('kategori'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $kategori = KategoriPelanggan::findOrFail($id);

        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategori_pelanggans,nama_kategori,' . $kategori->id,
        ]);

        $kategori->update($validated);

        return redirect()->route('kategori_pelanggan.index')
            ->with('success', 'Kategori Pelanggan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $kategori = KategoriPelanggan::findOrFail($id);
        $kategori->delete();

        return redirect()->route('kategori_pelanggan.index')
            ->with('success', 'Kategori Pelanggan berhasil dihapus.');
    }
}
