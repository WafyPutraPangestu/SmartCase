<x-layout>
    <h1>Edit Tiket</h1>

    <form action="{{ route('tiket.update', $ticket->id) }}" method="POST">
        @csrf
        @method('PUT')

        <label>Kategori Gangguan</label>
        <select name="kategori_gangguan_id">
            @foreach ($kategoriGangguan as $kg)
                <option value="{{ $kg->id }}" {{ $ticket->kategori_gangguan_id == $kg->id ? 'selected' : '' }}>
                    {{ $kg->nama_gangguan }}
                </option>
            @endforeach
        </select>

        <label>Judul</label>
        <input type="text" name="judul" value="{{ $ticket->judul }}" required>

        <label>Deskripsi</label>
        <textarea name="deskripsi" required>{{ $ticket->deskripsi }}</textarea>

        <label>Prioritas</label>
        <select name="prioritas">
            <option value="Rendah" {{ $ticket->prioritas == 'Rendah' ? 'selected' : '' }}>Rendah</option>
            <option value="Sedang" {{ $ticket->prioritas == 'Sedang' ? 'selected' : '' }}>Sedang</option>
            <option value="Tinggi" {{ $ticket->prioritas == 'Tinggi' ? 'selected' : '' }}>Tinggi</option>
        </select>

        <button type="submit">Update</button>
    </form>
</x-layout>
