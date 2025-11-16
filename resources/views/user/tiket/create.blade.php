<x-layout>
    <h1>Buat Tiket Baru</h1>

    <form action="{{ route('tiket.store') }}" method="POST">
        @csrf

        <label>Kategori Gangguan</label>
        <select name="kategori_gangguan_id" >
            {{-- @foreach ($kategoriGangguan as $kg)
                <option value="{{ $kg->id }}">{{ $kg->nama_gangguan }}</option>
            @endforeach --}}
        </select>

        <label>Judul</label>
        <input type="text" name="judul" required>

        <label>Deskripsi</label>
        <textarea name="deskripsi" required></textarea>

        <label>Prioritas</label>
        <select name="prioritas">
            <option value="">-</option>
            <option value="Rendah">Rendah</option>
            <option value="Sedang">Sedang</option>
            <option value="Tinggi">Tinggi</option>
        </select>

        <button type="submit">Kirim</button>
    </form>
</x-layout>
