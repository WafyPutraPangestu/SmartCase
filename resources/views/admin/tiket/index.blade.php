<x-layout>
    <div class="container mx-auto p-6" x-data="{ 
        showSuccessModal: false,
        successMessage: '',
        filterStatus: 'all',
        searchQuery: ''
    }">
        <!-- Header Section -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Dashboard Tiket</h1>
                <p class="text-gray-600 mt-1">Kelola dan monitor semua tiket pelanggan</p>
            </div>
            <div class="flex space-x-3">
                <button @click="window.location.reload()" 
                        class="btn btn-outline">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Refresh
                </button>
            </div>
        </div>

        <!-- Success Modal -->
        <div x-show="showSuccessModal" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 overflow-y-auto"
             style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-gray-900 bg-opacity-50" 
                     @click="showSuccessModal = false"></div>
                
                <div class="relative bg-white rounded-lg shadow-xl max-w-md w-full p-6"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform scale-90"
                     x-transition:enter-end="opacity-100 transform scale-100">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0 w-12 h-12 rounded-full bg-success-100 flex items-center justify-center">
                            <svg class="w-6 h-6 text-success-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <h3 class="ml-4 text-lg font-semibold text-gray-900">Status Diperbarui!</h3>
                    </div>
                    <p class="text-gray-600 mb-6" x-text="successMessage"></p>
                    <button @click="showSuccessModal = false" 
                            class="btn btn-success w-full">
                        Tutup
                    </button>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div class="card">
                <div class="card-body">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 w-12 h-12 rounded-full bg-primary-100 flex items-center justify-center">
                            <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-600">Total Tiket</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $tiket->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 w-12 h-12 rounded-full bg-warning-100 flex items-center justify-center">
                            <svg class="w-6 h-6 text-warning-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-600">Menunggu</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $tiket->where('status', 'Menunggu')->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 w-12 h-12 rounded-full bg-primary-100 flex items-center justify-center">
                            <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-600">Diproses</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $tiket->where('status', 'Diproses')->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 w-12 h-12 rounded-full bg-success-100 flex items-center justify-center">
                            <svg class="w-6 h-6 text-success-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-600">Selesai</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $tiket->where('status', 'Selesai')->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter & Search -->
        <div class="card mb-6">
            <div class="card-body">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
                    <div class="flex items-center space-x-3">
                        <label class="text-sm font-medium text-gray-700">Filter Status:</label>
                        <select x-model="filterStatus" class="form-input w-48">
                            <option value="all">Semua Status</option>
                            <option value="Menunggu">Menunggu</option>
                            <option value="Diproses">Diproses</option>
                            <option value="Selesai">Selesai</option>
                        </select>
                    </div>
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="text" 
                               x-model="searchQuery" 
                               placeholder="Cari tiket atau kode..." 
                               class="form-input w-64">
                    </div>
                </div>
            </div>
        </div>

        <!-- Table Card -->
        <div class="card">
            <div class="card-header">
                <h2 class="text-lg font-semibold text-gray-900">Daftar Tiket</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Kode Tiket
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Judul Tiket
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Pelanggan
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Kategori
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Prioritas
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($tiket as $t)
                            <tr class="hover:bg-gray-50 transition"
                                x-show="(filterStatus === 'all' || '{{ $t->status }}' === filterStatus) && 
                                        ('{{ strtolower($t->judul) }}'.includes(searchQuery.toLowerCase()) || 
                                         '{{ strtolower($t->kode_tiket) }}'.includes(searchQuery.toLowerCase()) ||
                                         '{{ strtolower($t->user->name) }}'.includes(searchQuery.toLowerCase()))">
                                
                                <!-- Kode Tiket -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <span class="inline-flex items-center gap-1 px-3 py-1.5 bg-gray-100 text-gray-700 rounded-md text-sm font-mono font-semibold border border-gray-300">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                                            </svg>
                                            {{ $t->kode_tiket }}
                                        </span>
                                    </div>
                                </td>

                                <!-- Judul -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-primary-100 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $t->judul }}</div>
                                            <div class="text-xs text-gray-500">{{ Str::limit($t->deskripsi, 50) }}</div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Pelanggan -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-secondary-100 flex items-center justify-center">
                                            <svg class="w-4 h-4 text-secondary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900">{{ $t->user->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $t->user->email }}</div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Kategori -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="badge badge-warning text-xs">
                                        {{ $t->kategori_gangguan_nama ?? '-' }}
                                    </span>
                                </td>

                                <!-- Prioritas -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($t->prioritas)
                                        @php
                                            $priorityColors = [
                                                'Rendah' => 'badge-secondary',
                                                'Sedang' => 'badge-warning',
                                                'Tinggi' => 'badge-danger'
                                            ];
                                        @endphp
                                        <div class="flex items-center gap-1">
                                            <span class="badge {{ $priorityColors[$t->prioritas] ?? 'badge-secondary' }} text-xs">
                                                {{ $t->prioritas }}
                                            </span>
                                            @if($t->ml_confidence)
                                                <span class="text-xs text-gray-500" title="AI Confidence">
                                                    ({{ round($t->ml_confidence * 100) }}%)
                                                </span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-400">-</span>
                                    @endif
                                </td>

                                <!-- Status -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div x-data="{status: '{{ $t->status }}', loading: false}">
                                        <select 
                                            x-model="status"
                                            :disabled="loading"
                                            @change="
                                                loading = true;
                                                fetch('{{ route('admin.tiket.updateStatus', $t->id) }}', {
                                                    method: 'PUT',
                                                    headers: {
                                                        'Content-Type': 'application/json',
                                                        'Accept': 'application/json',
                                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                                    },
                                                    body: JSON.stringify({status})
                                                })
                                                .then(res => res.json())
                                                .then(data => {
                                                    loading = false;
                                                    successMessage = 'Status tiket ' + '{{ $t->kode_tiket }}' + ' berhasil diperbarui menjadi ' + status;
                                                    showSuccessModal = true;
                                                    setTimeout(() => location.reload(), 1500);
                                                })
                                                .catch(err => {
                                                    loading = false;
                                                    alert('Terjadi kesalahan');
                                                });
                                            "
                                            :class="{
                                                'bg-warning-50 text-warning-700 border-warning-300': status === 'Menunggu',
                                                'bg-primary-50 text-primary-700 border-primary-300': status === 'Diproses',
                                                'bg-success-50 text-success-700 border-success-300': status === 'Selesai'
                                            }"
                                            class="form-input text-sm font-medium cursor-pointer">
                                            <option value="Menunggu">⏳ Menunggu</option>
                                            <option value="Diproses">⚡ Diproses</option>
                                            <option value="Selesai">✓ Selesai</option>
                                        </select>
                                        <div x-show="loading" class="text-xs text-gray-500 mt-1">
                                            Memperbarui...
                                        </div>
                                    </div>
                                </td>

                                <!-- Aksi -->
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    <a href="{{ route('admin.tiket.show', $t->id) }}" 
                                       class="inline-flex items-center px-3 py-1 bg-primary-50 text-primary-700 rounded hover:bg-primary-100 transition">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada tiket</h3>
                                    <p class="mt-1 text-sm text-gray-500">Tidak ada tiket yang perlu ditangani saat ini.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layout>