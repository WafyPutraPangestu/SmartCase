<x-layout>
    <div class="container mx-auto p-6 max-w-4xl" x-data="{ 
        showDeleteModal: false 
    }">
        <!-- Breadcrumb -->
        <nav class="flex mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('kategori_gangguan.index') }}" 
                       class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-primary-600">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                        </svg>
                        Daftar Kategori
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Detail Kategori</span>
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
                        <h1 class="text-3xl font-bold text-gray-900">Detail Kategori Gangguan</h1>
                        <p class="text-gray-600 mt-1">Informasi lengkap tentang kategori gangguan</p>
                    </div>
                </div>
                <span class="badge badge-primary text-base px-4 py-2">ID: #{{ $kategoriGangguan->id }}</span>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div x-show="showDeleteModal" 
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
                     @click="showDeleteModal = false"></div>
                
                <div class="relative bg-white rounded-lg shadow-xl max-w-md w-full p-6"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform scale-90"
                     x-transition:enter-end="opacity-100 transform scale-100">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0 w-12 h-12 rounded-full bg-danger-100 flex items-center justify-center">
                            <svg class="w-6 h-6 text-danger-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        <h3 class="ml-4 text-lg font-semibold text-gray-900">Konfirmasi Hapus</h3>
                    </div>
                    <p class="text-gray-600 mb-6">Apakah Anda yakin ingin menghapus kategori gangguan "<strong>{{ $kategoriGangguan->nama_gangguan }}</strong>"? Tindakan ini tidak dapat dibatalkan.</p>
                    <div class="flex space-x-3">
                        <button @click="showDeleteModal = false" 
                                type="button"
                                class="btn btn-outline flex-1">
                            Batal
                        </button>
                        <button @click="document.getElementById('delete-form').submit()" 
                                type="button"
                                class="btn btn-danger flex-1">
                            Ya, Hapus
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Detail Card -->
                <div class="card">
                    <div class="card-header">
                        <h2 class="text-lg font-semibold text-gray-900">Informasi Kategori</h2>
                    </div>
                    <div class="card-body space-y-6">
                        <!-- Nama Gangguan -->
                        <div>
                            <label class="form-label mb-2">Nama Gangguan</label>
                            <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                                <p class="text-lg font-medium text-gray-900">{{ $kategoriGangguan->nama_gangguan }}</p>
                            </div>
                        </div>

                        <!-- Deskripsi -->
                        <div>
                            <label class="form-label mb-2">Deskripsi</label>
                            <div class="p-4 bg-gray-50 rounded-lg border border-gray-200 min-h-32">
                                <p class="text-gray-700 leading-relaxed whitespace-pre-line">
                                    {{ $kategoriGangguan->deskripsi ?? 'Tidak ada deskripsi' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Timeline Card -->
                <div class="card">
                    <div class="card-header">
                        <h2 class="text-lg font-semibold text-gray-900">Riwayat Data</h2>
                    </div>
                    <div class="card-body">
                        <div class="space-y-4">
                            <!-- Created At -->
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-success-100 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-success-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                </div>
                                <div class="ml-4 flex-1">
                                    <div class="flex items-center justify-between">
                                        <h3 class="font-medium text-gray-900">Data Dibuat</h3>
                                        <span class="badge badge-success">Created</span>
                                    </div>
                                    <p class="text-sm text-gray-600 mt-1">
                                        {{ $kategoriGangguan->created_at->format('d F Y') }} pukul {{ $kategoriGangguan->created_at->format('H:i') }} WIB
                                    </p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        {{ $kategoriGangguan->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>

                            <div class="border-l-2 border-gray-200 ml-5 h-8"></div>

                            <!-- Updated At -->
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
                                        {{ $kategoriGangguan->updated_at->format('d F Y') }} pukul {{ $kategoriGangguan->updated_at->format('H:i') }} WIB
                                    </p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        {{ $kategoriGangguan->updated_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Action Card -->
                <div class="card">
                    <div class="card-header">
                        <h2 class="text-lg font-semibold text-gray-900">Aksi</h2>
                    </div>
                    <div class="card-body space-y-3">
                        <a href="{{ route('kategori_gangguan.edit', $kategoriGangguan->id) }}" 
                           class="btn btn-primary w-full">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Edit Kategori
                        </a>
                        
                        <button @click="showDeleteModal = true" 
                                type="button"
                                class="btn btn-danger w-full">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Hapus Kategori
                        </button>

                        <form id="delete-form" 
                              action="{{ route('kategori_gangguan.destroy', $kategoriGangguan->id) }}" 
                              method="POST" 
                              class="hidden">
                            @csrf
                            @method('DELETE')
                        </form>

                        <div class="border-t border-gray-200 my-4"></div>

                        <a href="{{ route('kategori_gangguan.index') }}" 
                           class="btn btn-outline w-full">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Kembali ke Daftar
                        </a>
                    </div>
                </div>

                <!-- Info Card -->
                <div class="card bg-primary-50">
                    <div class="card-body">
                        <div class="flex items-start">
                            <svg class="w-6 h-6 text-primary-600 mr-3 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div>
                                <h3 class="font-semibold text-primary-900 mb-2">Informasi</h3>
                                <p class="text-sm text-primary-800">
                                    Halaman ini menampilkan detail lengkap dari kategori gangguan yang dipilih. Anda dapat mengedit atau menghapus data melalui tombol aksi di samping.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statistics Card (Optional) -->
                <div class="card">
                    <div class="card-header">
                        <h2 class="text-lg font-semibold text-gray-900">Statistik</h2>
                    </div>
                    <div class="card-body space-y-3">
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <span class="text-sm text-gray-600">Total Karakter</span>
                            <span class="font-semibold text-gray-900">{{ strlen($kategoriGangguan->nama_gangguan) }}</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <span class="text-sm text-gray-600">Panjang Deskripsi</span>
                            <span class="font-semibold text-gray-900">{{ strlen($kategoriGangguan->deskripsi ?? '') }} karakter</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <span class="text-sm text-gray-600">Status</span>
                            <span class="badge badge-success">Aktif</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>