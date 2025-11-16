<x-layout>
    <h1>Detail Tiket</h1>

    <p><strong>Judul:</strong> {{ $ticket->judul }}</p>
    <p><strong>Kategori:</strong> {{ $ticket->kategoriGangguan?->nama_gangguan ?? 'Tidak ada kategori' }}</p>
    <p><strong>Deskripsi:</strong> {{ $ticket->deskripsi }}</p>
    <p><strong>Status:</strong> {{ $ticket->status }}</p>

    <a href="{{ route('tiket.edit', $ticket->id) }}">Edit</a>
</x-layout>
