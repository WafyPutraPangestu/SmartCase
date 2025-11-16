<x-layout>
    <div class="container mx-auto p-6 max-w-lg">
        <h1 class="text-2xl font-bold mb-6">Edit Kategori Gangguan</h1>

        <form action="{{ route('kategori_gangguan.update', $kategoriGangguan->id) }}" method="POST" class="space-y-4 bg-white p-6 rounded shadow">
            @csrf
            @method('PUT')

            <div>
                <label class="block mb-1 font-semibold">Nama Gangguan</label>
                <input type="text" name="nama_gangguan" 
                       class="w-full border p-2 rounded @error('nama_gangguan') border-red-500 @enderror" 
                       value="{{ old('nama_gangguan', $kategoriGangguan->nama_gangguan) }}">
                @error('nama_gangguan')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block mb-1 font-semibold">Deskripsi</label>
                <textarea name="deskripsi" class="w-full border p-2 rounded">{{ old('deskripsi', $kategoriGangguan->deskripsi) }}</textarea>
            </div>

            <div class="flex justify-end space-x-2">
                <a href="{{ route('kategori_gangguan.index') }}" 
                   class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Batal</a>
                <button type="submit" 
                        class="px-4 py-2 bg-yellow-600 text-white rounded hover:bg-yellow-700">Update</button>
            </div>
        </form>
    </div>
</x-layout>
