<x-layout>
    <div class="container mx-auto p-6 max-w-5xl" x-data="{ 
        showStatusModal: false,
        newStatus: '{{ $tiket->status }}',
        updateStatus() {
            fetch('{{ route('admin.tiket.updateStatus', $tiket->id) }}', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({status: this.newStatus})
            })
            .then(res => res.json())
            .then(data => {
                this.showStatusModal = false;
                location.reload();
            });
        }
    }">
        <!-- Breadcrumb -->
        <nav class="flex mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('tiket.index') }}" 
                       class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-primary-600">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                        </svg>
                        Daftar Tiket
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Detail Tiket</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="flex-shrink-0 w-12 h-12 rounded-full bg-primary-100 flex items-center justify-center">
                        <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h1 class="text-3xl font-bold text-gray-900">Detail Tiket</h1>
                        <p class="text-gray-600 mt-1">Informasi lengkap tentang tiket pelanggan</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <span class="badge badge-primary text-base px-4 py-2">ID: #{{ $tiket->id }}</span>
                    @php
                        $statusColors = [
                            'Menunggu' => 'badge-warning',
                            'Diproses' => 'badge-primary',
                            'Selesai' => 'badge-success'
                        ];
                    @endphp
                    <span class="badge {{ $statusColors[$tiket->status] ?? 'badge-primary' }} text-base px-4 py-2">
                        {{ $tiket->status }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Status Update Modal -->
        <div x-show="showStatusModal" 
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
                     @click="showStatusModal = false"></div>
                
                <div class="relative bg-white rounded-lg shadow-xl max-w-md w-full p-6"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform scale-90"
                     x-transition:enter-end="opacity-100 transform scale-100">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0 w-12 h-12 rounded-full bg-primary-100 flex items-center justify-center">
                            <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                        </div>
                        <h3 class="ml-4 text-lg font-semibold text-gray-900">Update Status Tiket</h3>
                    </div>
                    <div class="mb-6">
                        <label class="form-label">Pilih Status Baru:</label>
                        <select x-model="newStatus" class="form-input">
                            <option value="Menunggu">Menunggu</option>
                            <option value="Diproses">Diproses</option>
                            <option value="Selesai">Selesai</option>
                        </select>
                    </div>
                    <div class="flex space-x-3">
                        <button @click="showStatusModal = false" 
                                type="button"
                                class="btn btn-outline flex-1">
                            Batal
                        </button>
                        <button @click="updateStatus()" 
                                type="button"
                                class="btn btn-primary flex-1">
                            Update
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Tiket Info Card -->
                <div class="card">
                    <div class="card-header">
                        <h2 class="text-lg font-semibold text-gray-900">Informasi Tiket</h2>
                    </div>
                    <div class="card-body space-y-6">
                        <!-- Judul -->
                        <div>
                            <label class="form-label mb-2">Judul Tiket</label>
                            <div class="p-4 bg-primary-50 rounded-lg border-l-4 border-primary-500">
                                <p class="text-lg font-semibold text-gray-900">{{ $tiket->judul }}</p>
                            </div>
                        </div>

                        <!-- Deskripsi -->
                        <div>
                            <label class="form-label mb-2">Deskripsi Masalah</label>
                            <div class="p-4 bg-gray-50 rounded-lg border border-gray-200 min-h-32">
                                <p class="text-gray-700 leading-relaxed whitespace-pre-line">{{ $tiket->deskripsi }}</p>
                            </div>
                        </div>

                        <!-- Kategori & Prioritas -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="form-label mb-2">Kategori Gangguan</label>
                                <div class="p-4 bg-warning-50 rounded-lg border border-warning-200">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-warning-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                        </svg>
                                        <span class="font-medium text-gray-900">
                                            {{ $tiket->kategoriGangguan->nama_gangguan ?? '-' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label class="form-label mb-2">Prioritas (AI)</label>
                                <div class="p-4 bg-secondary-50 rounded-lg border border-secondary-200">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-secondary-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                        </svg>
                                        <span class="font-medium text-gray-900">
                                            {{ $tiket->prioritas ?? 'Tidak ada' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pelanggan Info -->
                <div class="card">
                    <div class="card-header">
                        <h2 class="text-lg font-semibold text-gray-900">Informasi Pelanggan</h2>
                    </div>
                    <div class="card-body">
                        <div class="flex items-center p-4 bg-gradient-to-r from-secondary-50 to-secondary-100 rounded-lg border-l-4 border-secondary-500">
                            <div class="flex-shrink-0 w-16 h-16 rounded-full bg-white shadow-lg flex items-center justify-center">
                                <svg class="w-8 h-8 text-secondary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <div class="ml-6">
                                <p class="text-sm font-medium text-secondary-600 uppercase tracking-wide">Nama Pelanggan</p>
                                <p class="text-xl font-bold text-gray-900 mt-1">{{ $tiket->user->name }}</p>
                                <p class="text-sm text-gray-600 mt-1">{{ $tiket->user->email }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Timeline -->
                <div class="card">
                    <div class="card-header">
                        <h2 class="text-lg font-semibold text-gray-900">Timeline</h2>
                    </div>
                    <div class="card-body">
                        <div class="space-y-4">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-success-100 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-success-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                </div>
                                <div class="ml-4 flex-1">
                                    <div class="flex items-center justify-between">
                                        <h3 class="font-medium text-gray-900">Tiket Dibuat</h3>
                                        <span class="badge badge-success">Created</span>
                                    </div>
                                    <p class="text-sm text-gray-600 mt-1">
                                        {{ $tiket->created_at->format('d F Y') }} pukul {{ $tiket->created_at->format('H:i') }} WIB
                                    </p>
                                    <p class="text-xs text-gray-500 mt-1">{{ $tiket->created_at->diffForHumans() }}</p>
                                </div>
                            </div>

                            <div class="border-l-2 border-gray-200 ml-5 h-8"></div>

                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-warning-100 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-warning-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                </div>
                                <div class="ml-4 flex-1">
                                    <div class="flex items-center justify-between">
                                        <h3 class="font-medium text-gray-900">Terakhir Diupdate</h3>
                                        <span class="badge badge-warning">Updated</span>
                                    </div>
                                    <p class="text-sm text-gray-600 mt-1">
                                        {{ $tiket->updated_at->format('d F Y') }} pukul {{ $tiket->updated_at->format('H:i') }} WIB
                                    </p>
                                    <p class="text-xs text-gray-500 mt-1">{{ $tiket->updated_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Quick Actions -->
                <div class="card">
                    <div class="card-header">
                        <h2 class="text-lg font-semibold text-gray-900">Aksi Cepat</h2>
                    </div>
                    <div class="card-body space-y-3">
                        <button @click="showStatusModal = true" 
                                class="btn btn-primary w-full">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Update Status
                        </button>

                        <div class="border-t border-gray-200 my-4"></div>

                        <a href="{{ route('admin.tiket.index') }}" 
                           class="btn btn-outline w-full">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Kembali ke Daftar
                        </a>
                    </div>
                </div>

                <!-- Status Info -->
                <div class="card {{ $tiket->status === 'Menunggu' ? 'bg-warning-50' : ($tiket->status === 'Diproses' ? 'bg-primary-50' : 'bg-success-50') }}">
                    <div class="card-body">
                        <div class="flex items-start">
                            @if($tiket->status === 'Menunggu')
                                <svg class="w-6 h-6 text-warning-600 mr-3 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            @elseif($tiket->status === 'Diproses')
                                <svg class="w-6 h-6 text-primary-600 mr-3 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                            @else
                                <svg class="w-6 h-6 text-success-600 mr-3 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            @endif
                            <div>
                                <h3 class="font-semibold text-gray-900 mb-2">Status: {{ $tiket->status }}</h3>
                                <p class="text-sm text-gray-700">
                                    @if($tiket->status === 'Menunggu')
                                        Tiket ini menunggu untuk ditangani oleh tim support.
                                    @elseif($tiket->status === 'Diproses')
                                        Tiket ini sedang dalam proses penanganan.
                                    @else
                                        Tiket ini telah selesai ditangani.
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statistics -->
                <div class="card">
                    <div class="card-header">
                        <h2 class="text-lg font-semibold text-gray-900">Statistik</h2>
                    </div>
                    <div class="card-body space-y-3">
                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                            <span class="text-sm text-gray-600">ID Tiket</span>
                            <span class="font-semibold text-gray-900">#{{ $tiket->id }}</span>
                        </div>
                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                            <span class="text-sm text-gray-600">Durasi</span>
                            <span class="font-semibold text-gray-900">{{ $tiket->created_at->diffInDays(now()) }} hari</span>
                        </div>
                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                            <span class="text-sm text-gray-600">Status Saat Ini</span>
                            <span class="badge {{ $statusColors[$tiket->status] ?? 'badge-primary' }}">{{ $tiket->status }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>