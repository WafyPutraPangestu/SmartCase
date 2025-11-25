<x-layout>
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8" 
         x-data="{
             searchQuery: '{{ $ticket->kategoriGangguan->nama_gangguan ?? '' }}',
             selectedCategory: {{ $ticket->kategori_gangguan_id ?? 'null' }},
             selectedCategoryName: '{{ $ticket->kategoriGangguan->nama_gangguan ?? '' }}',
             showDropdown: false,
             categories: {{ $kategoriGangguan->toJson() }},
             
             get filteredCategories() {
                 if (!this.searchQuery) return this.categories;
                 return this.categories.filter(cat => 
                     cat.nama_gangguan.toLowerCase().includes(this.searchQuery.toLowerCase())
                 );
             },
             
             get isNewCategory() {
                 if (!this.searchQuery.trim()) return false;
                 return !this.categories.some(cat => 
                     cat.nama_gangguan.toLowerCase() === this.searchQuery.trim().toLowerCase()
                 );
             },
             
             selectCategory(category) {
                 this.selectedCategory = category.id;
                 this.selectedCategoryName = category.nama_gangguan;
                 this.searchQuery = category.nama_gangguan;
                 this.showDropdown = false;
             },
             
             clearSelection() {
                 this.selectedCategory = null;
                 this.selectedCategoryName = '';
                 this.searchQuery = '';
                 this.showDropdown = true;
             },
             
             handleInput() {
                 this.showDropdown = true;
                 // Cek apakah input persis sama dengan kategori yang ada
                 const exact = this.categories.find(cat => 
                     cat.nama_gangguan.toLowerCase() === this.searchQuery.trim().toLowerCase()
                 );
                 if (exact) {
                     this.selectedCategory = exact.id;
                     this.selectedCategoryName = exact.nama_gangguan;
                 } else {
                     this.selectedCategory = null;
                     this.selectedCategoryName = '';
                 }
             }
         }">
        
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('tiket.index') }}" 
               class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali
            </a>
        </div>

        <!-- Error Alert -->
        @if(session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4" 
             x-data="{ show: true }" 
             x-show="show"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            <div class="flex items-start gap-3">
                <div class="flex-shrink-0">
                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-sm font-medium text-red-800">Gagal Memperbarui Tiket</h3>
                    <p class="text-sm text-red-700 mt-1">{{ session('error') }}</p>
                </div>
                <button type="button" @click="show = false" class="flex-shrink-0 text-red-500 hover:text-red-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
        @endif

        <!-- Success Alert -->
        @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4" 
             x-data="{ show: true }" 
             x-show="show"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            <div class="flex items-start gap-3">
                <div class="flex-shrink-0">
                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-sm text-green-700">{{ session('success') }}</p>
                </div>
                <button type="button" @click="show = false" class="flex-shrink-0 text-green-500 hover:text-green-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
        @endif

        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Edit Tiket</h1>
            <p class="text-gray-600">Perbarui informasi tiket #{{ $ticket->id }}</p>
        </div>

        <!-- Current Status Info -->
        <div class="mb-6 bg-gray-50 border border-gray-200 rounded-lg p-4">
            <div class="flex items-center gap-4 flex-wrap">
                <div class="flex items-center gap-2">
                    <span class="text-sm text-gray-500">Status:</span>
                    <span class="px-2 py-1 text-xs font-medium rounded-full 
                        @if($ticket->status == 'Menunggu') bg-yellow-100 text-yellow-800
                        @elseif($ticket->status == 'Diproses') bg-blue-100 text-blue-800
                        @elseif($ticket->status == 'Selesai') bg-green-100 text-green-800
                        @else bg-gray-100 text-gray-800 @endif">
                        {{ $ticket->status }}
                    </span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-sm text-gray-500">Prioritas:</span>
                    <span class="px-2 py-1 text-xs font-medium rounded-full
                        @if($ticket->prioritas == 'Urgent') bg-red-100 text-red-800
                        @elseif($ticket->prioritas == 'Tinggi') bg-orange-100 text-orange-800
                        @elseif($ticket->prioritas == 'Sedang') bg-yellow-100 text-yellow-800
                        @else bg-green-100 text-green-800 @endif">
                        {{ $ticket->prioritas }}
                    </span>
                </div>
                @if($ticket->ml_confidence)
                <div class="flex items-center gap-2">
                    <span class="text-sm text-gray-500">AI Confidence:</span>
                    <span class="text-sm font-medium text-purple-600">
                        {{ round($ticket->ml_confidence * 100, 1) }}%
                    </span>
                </div>
                @endif
            </div>
        </div>

        <!-- Form Card -->
        <div class="card">
            <div class="card-body">
                <form action="{{ route('tiket.update', $ticket->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Hidden Input: kirim kategori_gangguan (nama) -->
                    <input type="hidden" name="kategori_gangguan" :value="searchQuery.trim()">

                    <!-- Searchable Category Input -->
                    <div>
                        <label class="form-label flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                            Kategori Gangguan
                            <span class="text-red-500">*</span>
                        </label>
                        
                        <div class="relative">
                            <div class="relative">
                                <input 
                                    type="text" 
                                    x-model="searchQuery"
                                    @input="handleInput()"
                                    @focus="showDropdown = true"
                                    @click="showDropdown = true"
                                    placeholder="Ketik untuk mencari atau buat kategori baru..."
                                    class="form-input pr-10"
                                    autocomplete="off"
                                    maxlength="30"
                                    required
                                >
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </div>
                            </div>

                            <!-- Status Badge -->
                            <div class="mt-2" x-show="searchQuery.trim()">
                                <!-- Kategori Existing -->
                                <span x-show="!isNewCategory" 
                                      class="inline-flex items-center gap-2 bg-primary-100 text-primary-800 px-3 py-1 rounded-full text-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Kategori tersedia
                                </span>
                                <!-- Kategori Baru -->
                                <span x-show="isNewCategory" 
                                      class="inline-flex items-center gap-2 bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Akan dibuat kategori baru
                                </span>
                            </div>

                            <!-- Dropdown List -->
                            <div x-show="showDropdown && filteredCategories.length > 0" 
                                 @click.away="showDropdown = false"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-100 scale-100"
                                 x-transition:leave-end="opacity-0 scale-95"
                                 class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg max-h-60 overflow-auto"
                                 style="display: none;">
                                
                                <template x-for="category in filteredCategories" :key="category.id">
                                    <button 
                                        type="button"
                                        @click="selectCategory(category)"
                                        class="w-full text-left px-4 py-3 hover:bg-gray-50 transition-colors border-b border-gray-100 last:border-b-0">
                                        <div class="flex items-start gap-3">
                                            <div class="bg-primary-100 p-2 rounded-lg flex-shrink-0">
                                                <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                                </svg>
                                            </div>
                                            <div class="flex-1">
                                                <p class="font-medium text-gray-900" x-text="category.nama_gangguan"></p>
                                                <p class="text-sm text-gray-500 mt-1" x-text="category.deskripsi || 'Tidak ada deskripsi'"></p>
                                            </div>
                                        </div>
                                    </button>
                                </template>
                            </div>
                        </div>
                        
                        <p class="text-sm text-gray-500 mt-1">Pilih dari daftar atau ketik nama baru (maks 30 karakter)</p>
                        
                        @error('kategori_gangguan')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Judul -->
                    <div>
                        <label class="form-label flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                            </svg>
                            Judul Tiket
                            <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="judul" 
                            class="form-input @error('judul') border-red-300 @enderror" 
                            placeholder="Contoh: Tidak bisa login ke sistem"
                            value="{{ old('judul', $ticket->judul) }}"
                            required
                        >
                        @error('judul')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                        <p class="text-sm text-gray-500 mt-1">Berikan judul yang jelas dan singkat</p>
                    </div>

                    <!-- Deskripsi -->
                    <div>
                        <label class="form-label flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
                            </svg>
                            Deskripsi Masalah
                            <span class="text-red-500">*</span>
                        </label>
                        <textarea 
                            name="deskripsi" 
                            rows="6" 
                            class="form-input @error('deskripsi') border-red-300 @enderror"
                            placeholder="Jelaskan masalah Anda secara detail..."
                            required
                        >{{ old('deskripsi', $ticket->deskripsi) }}</textarea>
                        @error('deskripsi')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                        <p class="text-sm text-gray-500 mt-1">Minimal 20 karakter. Jelaskan masalah sejelas mungkin.</p>
                    </div>

                    <!-- AI Info Box -->
                    <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                        <div class="flex items-start gap-3">
                            <div class="bg-purple-100 p-2 rounded-lg flex-shrink-0">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-semibold text-purple-900 mb-1">Prioritas Otomatis</h4>
                                <p class="text-sm text-purple-700">
                                    Sistem AI akan menganalisis ulang kategori, judul, dan deskripsi untuk menentukan prioritas tiket baru setelah diperbarui.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex flex-col sm:flex-row gap-3 pt-4 border-t border-gray-200">
                        <a href="{{ route('tiket.index') }}" 
                           class="btn btn-outline flex-1 sm:flex-none justify-center">
                            Batal
                        </a>
                        <button type="submit" 
                                class="btn btn-primary flex-1 sm:flex-auto justify-center">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</x-layout>