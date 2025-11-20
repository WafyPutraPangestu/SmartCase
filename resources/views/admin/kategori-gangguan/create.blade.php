<x-layout>
    <div class="container mx-auto p-6 max-w-2xl" x-data="{ 
        showConfirmModal: false,
        formData: {},
        submitForm() {
            this.$refs.mainForm.submit();
        }
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
                        <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Tambah Baru</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Tambah Kategori Gangguan</h1>
            <p class="text-gray-600 mt-1">Isi formulir di bawah untuk menambahkan kategori gangguan baru</p>
        </div>

        <!-- Confirmation Modal -->
        <div x-show="showConfirmModal" 
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
                     @click="showConfirmModal = false"></div>
                
                <!-- Modal Content -->
                <div class="relative bg-white rounded-lg shadow-xl max-w-md w-full p-6"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform scale-90"
                     x-transition:enter-end="opacity-100 transform scale-100">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0 w-12 h-12 rounded-full bg-primary-100 flex items-center justify-center">
                            <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="ml-4 text-lg font-semibold text-gray-900">Konfirmasi Simpan</h3>
                    </div>
                    <p class="text-gray-600 mb-6">Apakah Anda yakin ingin menyimpan kategori gangguan ini?</p>
                    <div class="flex space-x-3">
                        <button @click="showConfirmModal = false" 
                                type="button"
                                class="btn btn-outline flex-1">
                            Batal
                        </button>
                        <button @click="submitForm()" 
                                type="button"
                                class="btn btn-primary flex-1">
                            Ya, Simpan
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Card -->
        <div class="card">
            <div class="card-header">
                <h2 class="text-lg font-semibold text-gray-900">Informasi Kategori</h2>
            </div>
            
            <form x-ref="mainForm" 
                  action="{{ route('kategori_gangguan.store') }}" 
                  method="POST" 
                  @submit.prevent="showConfirmModal = true">
                @csrf

                <div class="card-body space-y-6">
                    <!-- Nama Gangguan Field -->
                    <div>
                        <label class="form-label">
                            Nama Gangguan
                            <span class="text-danger-500">*</span>
                        </label>
                        <input type="text" 
                               name="nama_gangguan" 
                               class="form-input @error('nama_gangguan') !border-danger-500 @enderror" 
                               value="{{ old('nama_gangguan') }}"
                               placeholder="Contoh: Gangguan Jaringan"
                               required>
                        @error('nama_gangguan')
                            <p class="form-error">
                                <svg class="inline w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                        <p class="text-sm text-gray-500 mt-1">Masukkan nama kategori gangguan yang jelas dan deskriptif</p>
                    </div>

                    <!-- Deskripsi Field -->
                    <div>
                        <label class="form-label">
                            Deskripsi
                        </label>
                        <textarea name="deskripsi" 
                                  rows="5"
                                  class="form-input @error('deskripsi') !border-danger-500 @enderror"
                                  placeholder="Jelaskan detail tentang kategori gangguan ini...">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                            <p class="form-error">
                                <svg class="inline w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                        <p class="text-sm text-gray-500 mt-1">Berikan penjelasan detail mengenai kategori ini (opsional)</p>
                    </div>

                    <!-- Info Box -->
                    <div class="alert alert-info">
                        <div class="flex">
                            <svg class="w-5 h-5 text-primary-600 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                            <div>
                                <p class="font-medium">Tips Pengisian:</p>
                                <ul class="mt-2 text-sm space-y-1">
                                    <li>• Gunakan nama yang mudah dipahami</li>
                                    <li>• Deskripsi membantu identifikasi masalah lebih cepat</li>
                                    <li>• Pastikan kategori tidak duplikat</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="flex justify-between items-center">
                        <a href="{{ route('kategori_gangguan.index') }}" 
                           class="inline-flex items-center text-gray-600 hover:text-gray-900 transition">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Kembali ke Daftar
                        </a>
                        <div class="flex space-x-3">
                            <button type="reset" 
                                    class="btn btn-outline">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                                Reset
                            </button>
                            <button type="submit" 
                                    class="btn btn-primary">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Simpan Data
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Help Card -->
        <div class="mt-6 card bg-gray-50">
            <div class="card-body">
                <div class="flex items-start">
                    <svg class="w-6 h-6 text-primary-600 mr-3 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <h3 class="font-semibold text-gray-900 mb-2">Butuh Bantuan?</h3>
                        <p class="text-sm text-gray-600">
                            Jika Anda mengalami kesulitan dalam mengisi formulir ini, silakan hubungi administrator sistem atau baca panduan penggunaan aplikasi.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>