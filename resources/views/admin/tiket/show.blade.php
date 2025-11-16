<x-layout>
    <div class="p-6">
        <h1 class="text-2xl font-bold mb-4">Detail Tiket</h1>
    
        <div class="border p-4 rounded">
            <p><strong>ID:</strong> {{ $tiket->id }}</p>
            <p><strong>Judul:</strong> {{ $tiket->judul }}</p>
            <p><strong>Pelanggan:</strong> {{ $tiket->user->name }}</p>
            <p><strong>Kategori:</strong> {{ $tiket->kategoriGangguan->nama_gangguan ?? '-' }}</p>
            <p><strong>Deskripsi:</strong> {{ $tiket->deskripsi }}</p>
            <p><strong>Prioritas (AI):</strong> {{ $tiket->prioritas ?? '-' }}</p>
            <p><strong>Status:</strong> {{ $tiket->status }}</p>
        </div>
    
        <a href="{{ route('tiket.index') }}" class="mt-4 inline-block text-blue-600">← Kembali</a>
    </div>
    </x-layout>
    