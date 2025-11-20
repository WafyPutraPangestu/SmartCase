<x-layout>
    <div class="container mx-auto p-6" x-data="{ 
        showSuccessModal: {{ session('success') ? 'true' : 'false' }},
        deleteId: null,
        showDeleteModal: false,
        successMessage: '{{ session('success') }}'
    }">
        <!-- Header Section -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Daftar Kategori Gangguan</h1>
                <p class="text-gray-600 mt-1">Kelola kategori gangguan sistem Anda</p>
            </div>
            <a href="{{ route('kategori_gangguan.create') }}" 
               class="btn btn-primary">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Baru
            </a>
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
                <!-- Backdrop -->
                <div class="fixed inset-0 bg-gray-900 bg-opacity-50" 
                     @click="showSuccessModal = false"></div>
                
                <!-- Modal Content -->
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
                        <h3 class="ml-4 text-lg font-semibold text-gray-900">Berhasil!</h3>
                    </div>
                    <p class="text-gray-600 mb-6" x-text="successMessage"></p>
                    <button @click="showSuccessModal = false" 
                            class="btn btn-success w-full">
                        Tutup
                    </button>
                </div>
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
                <!-- Backdrop -->
                <div class="fixed inset-0 bg-gray-900 bg-opacity-50" 
                     @click="showDeleteModal = false"></div>
                
                <!-- Modal Content -->
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
                    <p class="text-gray-600 mb-6">Apakah Anda yakin ingin menghapus kategori gangguan ini? Tindakan ini tidak dapat dibatalkan.</p>
                    <div class="flex space-x-3">
                        <button @click="showDeleteModal = false" 
                                class="btn btn-outline flex-1">
                            Batal
                        </button>
                        <button @click="document.getElementById('delete-form-' + deleteId).submit()" 
                                class="btn btn-danger flex-1">
                            Hapus
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table Card -->
        <div class="card">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                #
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Nama Gangguan
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Deskripsi
                            </th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($kategori as $k)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <span class="badge badge-primary">{{ $loop->iteration }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $k->nama_gangguan }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-600">
                                        {{ Str::limit($k->deskripsi, 100) }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    <div class="flex items-center justify-center space-x-2">
                                        <a href="{{ route('kategori_gangguan.show', $k->id) }}" 
                                           class="inline-flex items-center px-3 py-1 bg-primary-50 text-primary-700 rounded hover:bg-primary-100 transition">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            Lihat
                                        </a>
                                        <a href="{{ route('kategori_gangguan.edit', $k->id) }}" 
                                           class="inline-flex items-center px-3 py-1 bg-warning-50 text-warning-700 rounded hover:bg-warning-100 transition">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                            Edit
                                        </a>
                                        <button @click="deleteId = {{ $k->id }}; showDeleteModal = true" 
                                                type="button"
                                                class="inline-flex items-center px-3 py-1 bg-danger-50 text-danger-700 rounded hover:bg-danger-100 transition">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                            Hapus
                                        </button>
                                        <form id="delete-form-{{ $k->id }}" 
                                              action="{{ route('kategori_gangguan.destroy', $k->id) }}" 
                                              method="POST" 
                                              class="hidden">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada data</h3>
                                    <p class="mt-1 text-sm text-gray-500">Mulai dengan menambahkan kategori gangguan baru.</p>
                                    <div class="mt-6">
                                        <a href="{{ route('kategori_gangguan.create') }}" class="btn btn-primary">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                            </svg>
                                            Tambah Kategori Baru
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layout>