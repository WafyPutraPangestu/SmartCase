<x-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="{ 
        showDeleteModal: false, 
        deleteId: null,
        showSuccessModal: false,
        successMessage: ''
    }">
        
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Daftar Tiket Saya</h1>
                <p class="text-gray-600 mt-1">Kelola dan pantau semua tiket support Anda</p>
            </div>
            <a href="{{ route('tiket.create') }}" 
               class="btn btn-primary inline-flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Buat Tiket Baru
            </a>
        </div>

        <!-- Tickets Grid -->
        @if($tickets->count() > 0)
        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
            @foreach ($tickets as $tiket)
            <div class="card hover:shadow-lg transition-shadow duration-200">
                <div class="card-body">
                    <!-- Kode Tiket & Status Badge -->
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex items-center gap-2">
                            <!-- Kode Tiket -->
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-gray-100 text-gray-700 rounded-md text-xs font-mono font-semibold">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                                </svg>
                                {{ $tiket->kode_tiket }}
                            </span>
                            
                            @php
                                $statusColors = [
                                    'Menunggu' => 'badge-primary',
                                    'Diproses' => 'badge-warning',
                                    'Selesai' => 'badge-success',
                                ];
                            @endphp
                            <span class="badge {{ $statusColors[$tiket->status] ?? 'badge-primary' }}">
                                {{ $tiket->status }}
                            </span>
                        </div>
                        
                        <!-- Priority Badge -->
                        @if($tiket->prioritas)
                        @php
                            $priorityColors = [
                                'Rendah' => 'badge-secondary',
                                'Sedang' => 'badge-warning',
                                'Tinggi' => 'badge-danger'
                            ];
                        @endphp
                        <span class="badge {{ $priorityColors[$tiket->prioritas] ?? 'badge-secondary' }} text-xs">
                            {{ $tiket->prioritas }}
                        </span>
                        @endif
                    </div>

                    <!-- Ticket Title -->
                    <h3 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2">
                        {{ $tiket->judul }}
                    </h3>

                    <!-- Category -->
                    @if($tiket->kategori_gangguan_nama)
                    <div class="flex items-center gap-1.5 text-sm text-gray-600 mb-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                        <span>{{ $tiket->kategori_gangguan_nama }}</span>
                    </div>
                    @endif

                    <!-- Ticket Meta -->
                    <div class="flex items-center gap-2 text-sm text-gray-500 mb-4">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>{{ $tiket->created_at->diffForHumans() }}</span>
                    </div>

                    <!-- Ticket Description Preview -->
                    @if($tiket->deskripsi)
                    <p class="text-sm text-gray-600 mb-4 line-clamp-2">
                        {{ Str::limit($tiket->deskripsi, 100) }}
                    </p>
                    @endif

                    <!-- Actions -->
                    <div class="flex items-center gap-2 pt-4 border-t border-gray-100">
                        <a href="{{ route('tiket.show', $tiket->id) }}" 
                           class="flex-1 btn btn-primary text-sm py-2">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            Lihat
                        </a>
                        
                        <a href="{{ route('tiket.edit', $tiket->id) }}" 
                           class="btn btn-outline text-sm py-2 px-3"
                           title="Edit Tiket">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </a>
                        
                        <button @click="deleteId = {{ $tiket->id }}; showDeleteModal = true" 
                                class="btn btn-danger text-sm py-2 px-3"
                                title="Hapus Tiket">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if(method_exists($tickets, 'links'))
        <div class="mt-8">
            {{ $tickets->links() }}
        </div>
        @endif

        @else
        <!-- Empty State -->
        <div class="card">
            <div class="card-body text-center py-12">
                <svg class="w-24 h-24 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Belum Ada Tiket</h3>
                <p class="text-gray-600 mb-6">Anda belum memiliki tiket support. Buat tiket baru untuk memulai.</p>
                <a href="{{ route('tiket.create') }}" class="btn btn-primary inline-flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Buat Tiket Pertama
                </a>
            </div>
        </div>
        @endif

        <!-- Delete Confirmation Modal -->
        <div x-show="showDeleteModal" 
             x-cloak
             class="fixed inset-0 z-50 overflow-y-auto" 
             style="display: none;">
            <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"
                 @click="showDeleteModal = false"></div>
            
            <div class="flex items-center justify-center min-h-screen p-4">
                <div x-show="showDeleteModal"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="bg-white rounded-lg shadow-xl max-w-md w-full p-6 relative z-10">
                    
                    <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 rounded-full mb-4">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>

                    <h3 class="text-lg font-semibold text-gray-900 text-center mb-2">
                        Hapus Tiket?
                    </h3>
                    <p class="text-gray-600 text-center mb-6">
                        Apakah Anda yakin ingin menghapus tiket ini? Tindakan ini tidak dapat dibatalkan.
                    </p>

                    <div class="flex gap-3">
                        <button @click="showDeleteModal = false" 
                                class="flex-1 btn btn-outline">
                            Batal
                        </button>
                        <form :action="`{{ url('tiket') }}/${deleteId}`" 
                              method="POST" 
                              class="flex-1"
                              @submit="showDeleteModal = false; setTimeout(() => { successMessage = 'Tiket berhasil dihapus!'; showSuccessModal = true; }, 300)">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full btn btn-danger">
                                Ya, Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Success Modal -->
        <div x-show="showSuccessModal" 
             x-cloak
             class="fixed inset-0 z-50 overflow-y-auto"
             style="display: none;">
            <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"
                 @click="showSuccessModal = false"></div>
            
            <div class="flex items-center justify-center min-h-screen p-4">
                <div x-show="showSuccessModal"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="bg-white rounded-lg shadow-xl max-w-md w-full p-6 relative z-10">
                    
                    <div class="flex items-center justify-center w-12 h-12 mx-auto bg-green-100 rounded-full mb-4">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>

                    <h3 class="text-lg font-semibold text-gray-900 text-center mb-2">
                        Berhasil!
                    </h3>
                    <p class="text-gray-600 text-center mb-6" x-text="successMessage"></p>

                    <button @click="showSuccessModal = false" 
                            class="w-full btn btn-success">
                        OK
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
        [x-cloak] { display: none !important; }
        
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
</x-layout>