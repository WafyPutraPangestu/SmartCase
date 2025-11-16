<x-layout>
    <h1>Daftar Tiket Saya</h1>

    <a href="{{ route('tiket.create') }}">Buat Tiket Baru</a>

    <ul>
        @foreach ($tickets as $tiket)
            <li>
                <a href="{{ route('tiket.show', $tiket->id) }}">
                    {{ $tiket->judul }} - {{ $tiket->status }}
                </a>
                <a href="{{ route('tiket.edit', $tiket->id) }}">(Edit)</a>
                <form action="{{ route('tiket.destroy', $tiket->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin hapus?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Hapus</button>
                </form>
                
            </li>
            
        @endforeach
    </ul>
</x-layout>
