<x-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="{
        // State variables
        showDeleteModal: false,
        deleteId: null,
        showSuccessModal: false,
        successMessage: '',
        showAIAnalysis: false,
        selectedTicket: null,
        
        // Methods
        openDeleteModal(id) {
            this.deleteId = id;
            this.showDeleteModal = true;
        },
        
        openAIAnalysis(ticket) {
            this.selectedTicket = ticket;
            this.showAIAnalysis = true;
        },
        
        closeModals() {
            this.showDeleteModal = false;
            this.showSuccessModal = false;
            this.showAIAnalysis = false;
            this.deleteId = null;
            this.selectedTicket = null;
        },
        
        submitDelete() {
            this.showDeleteModal = false;
            setTimeout(() => {
                this.successMessage = 'Tiket berhasil dihapus!';
                this.showSuccessModal = true;
            }, 300);
        }
    }" @keydown.escape="closeModals">
        
        <!-- Header dengan Stats Overview -->
        <div class="mb-8">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Dashboard Tiket Support</h1>
                    <p class="text-gray-600 mt-1">Kelola, pantau, dan analisis semua tiket support Anda</p>
                </div>
                <a href="{{ route('tiket.create') }}" 
                   class="btn btn-primary inline-flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Buat Tiket Baru
                </a>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4 mb-6">
                <div class="card">
                    <div class="card-body text-center p-4">
                        <div class="text-2xl font-bold text-primary-600">{{ $stats['total'] }}</div>
                        <div class="text-sm text-gray-600">Total Tiket</div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body text-center p-4">
                        <div class="text-2xl font-bold text-blue-600">{{ $stats['menunggu'] }}</div>
                        <div class="text-sm text-gray-600">Menunggu</div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body text-center p-4">
                        <div class="text-2xl font-bold text-warning-600">{{ $stats['diproses'] }}</div>
                        <div class="text-sm text-gray-600">Diproses</div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body text-center p-4">
                        <div class="text-2xl font-bold text-success-600">{{ $stats['selesai'] }}</div>
                        <div class="text-sm text-gray-600">Selesai</div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body text-center p-4">
                        <div class="text-2xl font-bold text-danger-600">{{ $stats['tinggi'] }}</div>
                        <div class="text-sm text-gray-600">Prioritas Tinggi</div>
                    </div>
                </div>
                <div class="card bg-gradient-to-r from-primary-500 to-secondary-500 text-white">
                    <div class="card-body text-center p-4">
                        <div class="text-2xl font-bold">{{ $aiInsights['total_predicted'] }}</div>
                        <div class="text-sm opacity-90">Dianalisis AI</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- AI Insights Section -->
        <div class="card mb-8">
            <div class="card-header">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    Analisis AI & Prediksi
                </h3>
            </div>
            <div class="card-body">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center">
                        <div class="flex items-center justify-center w-12 h-12 mx-auto bg-primary-100 rounded-full mb-3">
                            <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <h4 class="font-semibold text-gray-900 mb-1">Tiket Terprediksi</h4>
                        <p class="text-2xl font-bold text-primary-600">{{ $aiInsights['total_predicted'] }}</p>
                        <p class="text-sm text-gray-600 mt-1">Tiket dianalisis AI</p>
                    </div>
                    
                    <div class="text-center">
                        <div class="flex items-center justify-center w-12 h-12 mx-auto bg-warning-100 rounded-full mb-3">
                            <svg class="w-6 h-6 text-warning-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h4 class="font-semibold text-gray-900 mb-1">Akurasi Rata-rata</h4>
                        <p class="text-2xl font-bold text-warning-600">
                            {{ $aiInsights['avg_confidence'] ? number_format($aiInsights['avg_confidence'] * 100, 1) : '0' }}%
                        </p>
                        <p class="text-sm text-gray-600 mt-1">Tingkat kepercayaan AI</p>
                    </div>
                    
                    <div class="text-center">
                        <div class="flex items-center justify-center w-12 h-12 mx-auto bg-danger-100 rounded-full mb-3">
                            <svg class="w-6 h-6 text-danger-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        <h4 class="font-semibold text-gray-900 mb-1">Prioritas Tinggi</h4>
                        <p class="text-2xl font-bold text-danger-600">{{ $aiInsights['high_priority_count'] }}</p>
                        <p class="text-sm text-gray-600 mt-1">Diprediksi penting oleh AI</p>
                    </div>
                </div>
                
                <div class="mt-6 p-4 bg-primary-50 rounded-lg border border-primary-200">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-primary-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                        </svg>
                        <div>
                            <h4 class="font-semibold text-primary-900 mb-1">Bagaimana AI Bekerja?</h4>
                            <p class="text-sm text-primary-700">
                                Sistem AI kami menganalisis deskripsi tiket Anda untuk memprediksi kategori gangguan, 
                                menilai tingkat prioritas, dan memberikan rekomendasi solusi berdasarkan data historis.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter & Search Section -->
        <div class="card mb-6">
            <div class="card-body">
                <form method="GET" id="filterForm" class="flex flex-col lg:flex-row gap-4">
                    <!-- Search -->
                    <div class="flex-1">
                        <div class="flex gap-2">
                            <div class="flex-1 relative">
                                <input type="text" 
                                       name="search" 
                                       value="{{ request('search') }}"
                                       placeholder="Cari tiket berdasarkan kode, judul, atau deskripsi..."
                                       class="form-input pl-10">
                                <svg class="w-4 h-4 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400" 
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                            <button type="submit" class="btn btn-primary px-6">
                                Cari
                            </button>
                        </div>
                    </div>

                    <!-- Filters -->
                    <div class="flex  gap-2">
                        <select name="status" class="form-input text-sm" onchange="document.getElementById('filterForm').submit()">
                            <option value="">Semua Status</option>
                            <option value="Menunggu" {{ request('status') == 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                            <option value="Diproses" {{ request('status') == 'Diproses' ? 'selected' : '' }}>Diproses</option>
                            <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                        </select>

                        <select name="prioritas" class="form-input text-sm" onchange="document.getElementById('filterForm').submit()">
                            <option value="">Semua Prioritas</option>
                            <option value="Tinggi" {{ request('prioritas') == 'Tinggi' ? 'selected' : '' }}>Tinggi</option>
                            <option value="Sedang" {{ request('prioritas') == 'Sedang' ? 'selected' : '' }}>Sedang</option>
                            <option value="Rendah" {{ request('prioritas') == 'Rendah' ? 'selected' : '' }}>Rendah</option>
                        </select>

                        <select name="kategori" class="form-input text-sm" onchange="document.getElementById('filterForm').submit()">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category }}" {{ request('kategori') == $category ? 'selected' : '' }}>
                                    {{ $category }}
                                </option>
                            @endforeach
                        </select>

                        <select name="sort" class="form-input text-sm" onchange="document.getElementById('filterForm').submit()">
                            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Terbaru</option>
                            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Terlama</option>
                            <option value="priority" {{ request('sort') == 'priority' ? 'selected' : '' }}>Prioritas</option>
                            <option value="status" {{ request('sort') == 'status' ? 'selected' : '' }}>Status</option>
                        </select>

                        <!-- Reset Filters -->
                        @if(request()->hasAny(['search', 'status', 'prioritas', 'kategori', 'sort']))
                        <a href="{{ route('dashboard.index') }}" class="btn btn-outline text-sm">
                            Reset
                        </a>
                        @endif
                    </div>
                </form>

                <!-- Active Filters Badges -->
                @if(request()->hasAny(['search', 'status', 'prioritas', 'kategori']))
                <div class="mt-4 flex flex-wrap gap-2">
                    @if(request('search'))
                    <span class="badge badge-primary inline-flex items-center gap-1">
                        Pencarian: "{{ request('search') }}"
                        <a href="{{ request()->fullUrlWithQuery(['search' => null]) }}" class="hover:text-white">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </a>
                    </span>
                    @endif
                    
                    @if(request('status'))
                    <span class="badge badge-success inline-flex items-center gap-1">
                        Status: {{ request('status') }}
                        <a href="{{ request()->fullUrlWithQuery(['status' => null]) }}" class="hover:text-white">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </a>
                    </span>
                    @endif
                    
                    @if(request('prioritas'))
                    <span class="badge badge-warning inline-flex items-center gap-1">
                        Prioritas: {{ request('prioritas') }}
                        <a href="{{ request()->fullUrlWithQuery(['prioritas' => null]) }}" class="hover:text-white">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </a>
                    </span>
                    @endif
                    
                    @if(request('kategori'))
                    <span class="badge badge-secondary inline-flex items-center gap-1">
                        Kategori: {{ request('kategori') }}
                        <a href="{{ request()->fullUrlWithQuery(['kategori' => null]) }}" class="hover:text-white">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </a>
                    </span>
                    @endif
                </div>
                @endif
            </div>
        </div>

        <!-- Tickets Grid dengan Enhanced Features -->
        @if($tickets->count() > 0)
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @foreach ($tickets as $tiket)
            <div class="card hover:shadow-xl transition-all duration-300 border border-gray-200 hover:border-primary-300">
                <div class="card-body">
                    <!-- Header dengan Kode dan Status -->
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-gray-100 text-gray-700 rounded-md text-xs font-mono font-semibold border">
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
                            <span class="badge {{ $statusColors[$tiket->status] ?? 'badge-primary' }} text-xs">
                                {{ $tiket->status }}
                            </span>
                        </div>
                        
                        <!-- AI Indicator -->
                        @if($tiket->ml_predicted_at)
                        <button @click="openAIAnalysis({{ json_encode($tiket) }})" 
                                class="p-1.5 rounded-full bg-primary-100 text-primary-600 hover:bg-primary-200 transition-colors"
                                title="Lihat Analisis AI">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </button>
                        @endif
                    </div>

                    <!-- Priority Badge -->
                    @if($tiket->prioritas)
                    <div class="mb-3">
                        @php
                            $priorityColors = [
                                'Rendah' => 'badge-secondary',
                                'Sedang' => 'badge-warning',
                                'Tinggi' => 'badge-danger'
                            ];
                            $priorityIcons = [
                                'Rendah' => 'M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2',
                                'Sedang' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z',
                                'Tinggi' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z'
                            ];
                        @endphp
                        <span class="badge {{ $priorityColors[$tiket->prioritas] ?? 'badge-secondary' }} inline-flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $priorityIcons[$tiket->prioritas] }}"/>
                            </svg>
                            {{ $tiket->prioritas }}
                        </span>
                    </div>
                    @endif

                    <!-- Ticket Title -->
                    <h3 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2 hover:text-primary-600 transition-colors cursor-pointer"
                        onclick="window.location.href='{{ route('tiket.show', $tiket->id) }}'">
                        {{ $tiket->judul }}
                    </h3>

                    <!-- Category & Timestamp -->
                    <div class="space-y-2 mb-4">
                        @if($tiket->kategori_gangguan_nama)
                        <div class="flex items-center gap-1.5 text-sm text-gray-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                            <span>{{ $tiket->kategori_gangguan_nama }}</span>
                        </div>
                        @endif

                        <div class="flex items-center gap-2 text-sm text-gray-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>{{ $tiket->created_at->diffForHumans() }}</span>
                            @if($tiket->created_at != $tiket->updated_at)
                            <span class="text-xs bg-gray-100 px-2 py-1 rounded">Diupdate</span>
                            @endif
                        </div>
                    </div>

                    <!-- Ticket Description Preview -->
                    @if($tiket->deskripsi)
                    <p class="text-sm text-gray-600 mb-4 line-clamp-3 leading-relaxed">
                        {{ Str::limit($tiket->deskripsi, 120) }}
                    </p>
                    @endif

                    <!-- AI Confidence Bar -->
                    @if($tiket->ml_confidence)
                    <div class="mb-4">
                        <div class="flex justify-between text-xs text-gray-600 mb-1">
                            <span>Kepercayaan AI</span>
                            <span>{{ number_format($tiket->ml_confidence * 100, 1) }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-primary-600 h-2 rounded-full transition-all duration-500" 
                                 style="width: {{ $tiket->ml_confidence * 100 }}%"></div>
                        </div>
                    </div>
                    @endif

                    <!-- Progress Tracking -->
                    <div class="mb-4">
                        <div class="flex justify-between text-xs text-gray-600 mb-2">
                            <span>Status Progress</span>
                        </div>
                        <div class="flex items-center justify-between">
                            @php
                                $steps = ['Menunggu', 'Diproses', 'Selesai'];
                                $currentStep = array_search($tiket->status, $steps);
                            @endphp
                            @foreach($steps as $index => $step)
                                <div class="flex flex-col items-center">
                                    <div class="w-6 h-6 rounded-full flex items-center justify-center text-xs
                                        {{ $index <= $currentStep ? 'bg-primary-600 text-white' : 'bg-gray-300 text-gray-600' }}">
                                        {{ $index + 1 }}
                                    </div>
                                    <span class="text-xs mt-1 {{ $index <= $currentStep ? 'text-primary-600 font-medium' : 'text-gray-500' }}">
                                        {{ $step }}
                                    </span>
                                </div>
                                @if($index < count($steps) - 1)
                                    <div class="flex-1 h-1 mx-1 {{ $index < $currentStep ? 'bg-primary-600' : 'bg-gray-300' }}"></div>
                                @endif
                            @endforeach
                        </div>
                    </div>

                    <!-- Enhanced Actions -->
                    <div class="flex items-center gap-2 pt-4 border-t border-gray-100">
                        <a href="{{ route('tiket.show', $tiket->id) }}" 
                           class="flex-1 btn btn-primary text-sm py-2 inline-flex items-center justify-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            Detail
                        </a>
                        
                        <div class="flex gap-1">
                            <a href="{{ route('tiket.edit', $tiket->id) }}" 
                               class="btn btn-outline text-sm py-2 px-3 hover:bg-gray-50 transition-colors"
                               title="Edit Tiket">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                            
                            <button @click="openDeleteModal({{ $tiket->id }})" 
                                    class="btn btn-danger text-sm py-2 px-3 hover:bg-danger-700 transition-colors"
                                    title="Hapus Tiket">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
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
        <!-- Enhanced Empty State -->
        <div class="card">
            <div class="card-body text-center py-16">
                <div class="w-24 h-24 mx-auto mb-6 bg-primary-100 rounded-full flex items-center justify-center">
                    <svg class="w-12 h-12 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-3">Belum Ada Tiket</h3>
                <p class="text-gray-600 mb-6 max-w-md mx-auto">
                    Anda belum memiliki tiket support. Buat tiket pertama Anda untuk melaporkan gangguan 
                    dan dapatkan bantuan dari tim support kami.
                </p>
                <div class="flex flex-col sm:flex-row gap-3 justify-center">
                    <a href="{{ route('tiket.create') }}" class="btn btn-primary inline-flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Buat Tiket Pertama
                    </a>
                    <button class="btn btn-outline inline-flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Pelajari Cara Membuat Tiket
                    </button>
                </div>
            </div>
        </div>
        @endif

        <!-- AI Analysis Modal -->
        <div x-show="showAIAnalysis" 
             x-cloak
             class="fixed inset-0 z-50 overflow-y-auto" 
             style="display: none;">
            <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"
                 @click="closeModals()"></div>
            
            <div class="flex items-center justify-center min-h-screen p-4">
                <div x-show="showAIAnalysis"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="bg-white rounded-lg shadow-xl max-w-2xl w-full p-6 relative z-10">
                    
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                            <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                            Analisis AI
                        </h3>
                        <button @click="closeModals()" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <template x-if="selectedTicket">
                        <div class="space-y-6">
                            <!-- Ticket Info -->
                            <div>
                                <h4 class="font-semibold text-gray-900 mb-2" x-text="selectedTicket.judul"></h4>
                                <div class="grid grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <span class="text-gray-600">Kode Tiket:</span>
                                        <span class="font-mono ml-2" x-text="selectedTicket.kode_tiket"></span>
                                    </div>
                                    <div>
                                        <span class="text-gray-600">Kategori:</span>
                                        <span class="ml-2" x-text="selectedTicket.kategori_gangguan_nama || 'Tidak tersedia'"></span>
                                    </div>
                                </div>
                            </div>

                            <!-- AI Predictions -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="bg-primary-50 p-4 rounded-lg">
                                    <h5 class="font-semibold text-primary-900 mb-2">Prediksi Prioritas</h5>
                                    <div class="flex items-center gap-3">
                                        <span class="text-2xl font-bold text-primary-600" x-text="selectedTicket.prioritas"></span>
                                        <div class="flex-1">
                                            <div class="flex justify-between text-xs text-primary-700 mb-1">
                                                <span>Kepercayaan AI</span>
                                                <span x-text="(selectedTicket.ml_confidence * 100).toFixed(1) + '%'"></span>
                                            </div>
                                            <div class="w-full bg-primary-200 rounded-full h-2">
                                                <div class="bg-primary-600 h-2 rounded-full transition-all duration-500" 
                                                     :style="'width: ' + (selectedTicket.ml_confidence * 100) + '%'"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-warning-50 p-4 rounded-lg">
                                    <h5 class="font-semibold text-warning-900 mb-2">Status Analisis</h5>
                                    <div class="flex items-center gap-2">
                                        <svg class="w-5 h-5 text-warning-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span class="text-warning-700" x-text="selectedTicket.ml_predicted_at ? 'Dianalisis pada ' + new Date(selectedTicket.ml_predicted_at).toLocaleDateString('id-ID') : 'Belum dianalisis'"></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Explanation -->
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h5 class="font-semibold text-gray-900 mb-2">Penjelasan AI</h5>
                                <p class="text-sm text-gray-700">
                                    Berdasarkan analisis machine learning, sistem memprediksi tiket ini memiliki prioritas 
                                    <span class="font-semibold" x-text="selectedTicket.prioritas"></span> dengan tingkat 
                                    kepercayaan <span class="font-semibold" x-text="(selectedTicket.ml_confidence * 100).toFixed(1) + '%'"></span>. 
                                    Prediksi ini didasarkan pada pola historis tiket serupa dan karakteristik deskripsi yang diberikan.
                                </p>
                            </div>

                            <!-- Recommendations -->
                            <div class="bg-success-50 p-4 rounded-lg">
                                <h5 class="font-semibold text-success-900 mb-2">Rekomendasi</h5>
                                <ul class="text-sm text-success-700 space-y-1">
                                    <template x-if="selectedTicket.prioritas === 'Tinggi'">
                                        <li>• Tim support akan segera menangani tiket ini</li>
                                    </template>
                                    <template x-if="selectedTicket.prioritas === 'Sedang'">
                                        <li>• Tiket akan ditangani dalam 24-48 jam</li>
                                    </template>
                                    <template x-if="selectedTicket.prioritas === 'Rendah'">
                                        <li>• Tiket akan ditangani dalam 3-5 hari kerja</li>
                                    </template>
                                    <li>• Pastikan informasi kontak Anda terbaru</li>
                                    <li>• Siapkan informasi tambahan jika diperlukan</li>
                                </ul>
                            </div>
                        </div>
                    </template>

                    <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-200">
                        <button @click="closeModals()" class="btn btn-outline">
                            Tutup
                        </button>
                        <a :href="'/tiket/' + selectedTicket?.id" class="btn btn-primary">
                            Lihat Detail Lengkap
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div x-show="showDeleteModal" 
             x-cloak
             class="fixed inset-0 z-50 overflow-y-auto" 
             style="display: none;">
            <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"
                 @click="closeModals()"></div>
            
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
                        <button @click="closeModals()" 
                                class="flex-1 btn btn-outline">
                            Batal
                        </button>
                        <form :action="`{{ url('tiket') }}/${deleteId}`" 
                              method="POST" 
                              class="flex-1"
                              @submit="submitDelete()">
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
                 @click="closeModals()"></div>
            
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

                    <button @click="closeModals()" 
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
        
        .line-clamp-3 {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
</x-layout>