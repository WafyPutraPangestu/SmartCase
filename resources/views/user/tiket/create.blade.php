<x-layout>
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8" 
         x-data="{
             searchQuery: '',
             selectedCategory: null,
             selectedCategoryName: '',
             showDropdown: false,
             categories: {{ $kategoriGangguan->toJson() }},
             
             get filteredCategories() {
                 if (!this.searchQuery) return this.categories;
                 return this.categories.filter(cat => 
                     cat.nama_gangguan.toLowerCase().includes(this.searchQuery.toLowerCase())
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

        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Buat Tiket Baru</h1>
            <p class="text-gray-600">Laporkan masalah Anda dan sistem AI kami akan menentukan prioritas secara otomatis</p>
        </div>

        <!-- AI Info Alert -->
        <div class="alert alert-info mb-6 flex items-start gap-3">
            <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
            </svg>
            <div>
                <strong class="font-semibold">Prioritas Otomatis dengan AI</strong>
                <p class="text-sm mt-1">Sistem kami akan menganalisis tiket Anda dan menentukan tingkat prioritas secara otomatis menggunakan Machine Learning.</p>
            </div>
        </div>

        <!-- Form Card -->
        <div class="card">
            <div class="card-body">
                <form action="{{ route('tiket.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Hidden Input for Selected Category -->
                    <input type="hidden" name="kategori_gangguan_id" x-model="selectedCategory">

                    <!-- Searchable Category Dropdown -->
                    <div>
                        <label class="form-label flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                            Kategori Gangguan
                            <span class="text-red-500">*</span>
                        </label>
                        
                        <div class="relative">
                            <!-- Search Input -->
                            <div class="relative">
                                <input 
                                    type="text" 
                                    x-model="searchQuery"
                                    @focus="showDropdown = true"
                                    @click="showDropdown = true"
                                    placeholder="Cari atau pilih kategori gangguan..."
                                    class="form-input pr-10"
                                    :class="{ 'border-red-300': !selectedCategory && $el.closest('form').querySelector('button[type=submit]').classList.contains('was-validated') }"
                                    autocomplete="off"
                                >
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </div>
                            </div>

                            <!-- Selected Category Badge -->
                            <div x-show="selectedCategory" 
                                 x-transition
                                 class="mt-2 inline-flex items-center gap-2 bg-primary-100 text-primary-800 px-3 py-1 rounded-full text-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span x-text="selectedCategoryName"></span>
                                <button type="button" @click="clearSelection()" class="hover:text-primary-900">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>

                            <!-- Dropdown List -->
                            <div x-show="showDropdown && !selectedCategory" 
                                 @click.away="showDropdown = false"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-100 scale-100"
                                 x-transition:leave-end="opacity-0 scale-95"
                                 class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg max-h-60 overflow-auto"
                                 style="display: none;">
                                
                                <!-- Categories List -->
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

                                <!-- No Results -->
                                <div x-show="filteredCategories.length === 0" 
                                     class="px-4 py-8 text-center text-gray-500">
                                    <svg class="w-12 h-12 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <p>Kategori tidak ditemukan</p>
                                </div>
                            </div>
                        </div>
                        
                        @error('kategori_gangguan_id')
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
                            class="form-input" 
                            placeholder="Contoh: Tidak bisa login ke sistem"
                            value="{{ old('judul') }}"
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
                            class="form-input"
                            placeholder="Jelaskan masalah Anda secara detail. Semakin detail informasi yang Anda berikan, semakin akurat sistem AI dalam menentukan prioritas..."
                            required
                        >{{ old('deskripsi') }}</textarea>
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
                                    Sistem AI akan menganalisis kategori gangguan, judul, dan deskripsi untuk menentukan prioritas tiket Anda (Rendah, Sedang, Tinggi, atau Urgent).
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                            Kirim Tiket
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