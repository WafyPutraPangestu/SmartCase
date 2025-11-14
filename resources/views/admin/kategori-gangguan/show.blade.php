<x-layout>
    <div class="container mx-auto p-6 max-w-lg">
        <h1 class="text-2xl font-bold mb-6">Detail Kategori Gangguan</h1>

        <div class="bg-white p-6 rounded shadow space-y-4">
            <div>
                <h2 class="font-semibold">Nama Gangguan:</h2>
                <p>{{ $kategoriGangguan->nama_gangguan }}</p>
            </div>

            <div>
                <h2 class="font-semibold">Deskripsi:</h2>
                <p>{{ $kategoriGangguan->deskripsi ?? '-' }}</p>
            </div>

            <div class="flex justify-end space-x-2">
                <a href="{{ route('kategori_gangguan.edit', $kategoriGangguan->id) }}" 
                   class="px-4 py-2 bg-yellow-600 text-white rounded hover:bg-yellow-700">Edit</a>
                <a href="{{ route('kategori_gangguan.index') }}" 
                   class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Kembali</a>
            </div>
        </div>
    </div>
</x-layout>
