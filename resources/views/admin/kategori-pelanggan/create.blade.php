<x-layout>
    <h1>Tambah Kategori Pelanggan</h1>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                   <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('kategori_pelanggan.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="nama_kategori" class="form-label">Nama Kategori</label>
            <input type="text" class="form-control" name="nama_kategori" value="{{ old('nama_kategori') }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('kategori_pelanggan.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</x-layout>
