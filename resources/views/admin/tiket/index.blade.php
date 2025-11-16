<x-layout>
    <div class="p-6">
        <h1 class="text-2xl font-bold mb-4">Daftar Tiket</h1>
    
        <table class="w-full border">
            <thead>
                <tr class="bg-gray-100">
                    <th class="p-2">ID</th>
                    <th class="p-2">Judul</th>
                    <th class="p-2">Pelanggan</th>
                    <th class="p-2">Kategori</th>
                    <th class="p-2">Status</th>
                    <th class="p-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tiket as $t)
                <tr class="border">
                    <td class="p-2">{{ $t->id }}</td>
                    <td class="p-2">{{ $t->judul }}</td>
                    <td class="p-2">{{ $t->user->name }}</td>
                    <td class="p-2">{{ $t->kategoriGangguan->nama_gangguan ?? '-' }}</td>
    
                    <td class="p-2">
                        <div x-data="{status: '{{ $t->status }}'}">
                            <select 
                                x-model="status"
                                @change="
                                fetch('{{ route('admin.tiket.updateStatus', $t->id) }}', {
                                    method: 'PUT',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'Accept': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    },
                                    body: JSON.stringify({status})
                                }).then(res => res.json())
                            "
                            
                                class="border p-1 rounded"
                            >
                                <option value="Menunggu">Menunggu</option>
                                <option value="Diproses">Diproses</option>
                                <option value="Selesai">Selesai</option>
                            </select>
                        </div>
                    </td>
    
                    <td class="p-2">
                        <a href="{{ route('admin.tiket.show', $t->id) }}" class="text-blue-600">Detail</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    </x-layout>
    