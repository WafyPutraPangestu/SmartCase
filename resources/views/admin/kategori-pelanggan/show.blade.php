<x-layout>
    <h1>Detail Kategori Pelanggan</h1>

    <table class="table table-bordered">
        <tr>
            <th>ID</th>
            <td>{{ $kategori->id }}</td>
        </tr>
        <tr>
            <th>Nama Kategori</th>
            <td>{{ $kategori->nama_kategori }}</td>
        </tr>
    </table>

    <a href="{{ route('kategori_pelanggan.index') }}" class="btn btn-secondary">Kembali</a>
</x-layout>
