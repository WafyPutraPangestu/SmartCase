<x-layout>
    <div class="container mx-auto p-6 max-w-2xl" x-data="{ 
        showConfirmModal: false,
        showCancelModal: false,
        showErrorModal: {{ $errors->any() ? 'true' : 'false' }},
        hasChanges: false,
        originalData: {
            nama_kategori: '{{ $kategori->nama_kategori }}'
        },
        checkChanges() {
            const currentNama = this.$refs.namaInput.value;
            this.hasChanges = currentNama !== this.originalData.nama_kategori;
        },
        submitForm() {
            this.$refs.mainForm.submit();
        }
    }">
        <!-- Breadcrumb -->
        <nav class="flex mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('kategori_pelanggan.index') }}" 
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
                        <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Edit Kategori</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 w-12 h-12 rounded-full bg-warning-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-warning-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h1 class="text-3xl font-bold text-gray-900">Edit Kategori Pelanggan</h1>
                    <p class="text-gray-600 mt-1">Perbarui informasi kategori pelanggan yang dipilih</p>
                </div>
            </div>
        </div>

        <!-- Error Modal -->
        <div x-show="showErrorModal" 
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
                     @click="showErrorModal = false"></div>
                
                <div class="relative bg-white rounded-lg shadow-xl max-w-md w-full p-6"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform scale-90"
                     x-transition:enter-end="opacity-100 transform scale-100">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0 w-12 h-12 rounded-full bg-danger-100 flex items-center justify-center">
                            <svg class="w-6 h-6 text-danger-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <h3 class="ml-4 text-lg font-semibold text-gray-900">Terdapat Kesalahan</h3>
                    </div>
                    <div class="text-gray-600 mb-6">
                        <p class="mb-2">Mohon perbaiki kesalahan berikut:</p>
                        <ul class="list-disc list-inside space-y-1 text-sm">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <button @click="showErrorModal = false" 
                            class="btn btn-danger w-full">
                        Tutup
                    </button>
                </div>
            </div>
        </div>

        <!-- Confirm Update Modal -->
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
                <div class="fixed inset-0 bg-gray-900 bg-opacity-50" 
                     @click="showConfirmModal = false"></div>
                
                <div class="relative bg-white rounded-lg shadow-xl max-w-md w-full p-6"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform scale-90"
                     x-transition:enter-end="opacity-100 transform scale-100">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0 w-12 h-12 rounded-full bg-warning-100 flex items-center justify-center">
                            <svg class="w-6 h-6 text-warning-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </div>
                        <h3 class="ml-4 text-lg font-semibold text-gray-900">Konfirmasi Update</h3>
                    </div>
                    <p class="text-gray-600 mb-6">Apakah Anda yakin ingin menyimpan perubahan pada kategori pelanggan ini?</p>
                    <div class="flex space-x-3">
                        <button @click="showConfirmModal = false" 
                                type="button"
                                class="btn btn-outline flex-1">
                            Batal
                        </button>
                        <button @click="submitForm()" 
                                type="button"
                                class="btn btn-success flex-1">
                            Ya, Update
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cancel Confirmation Modal -->
        <div x-show="showCancelModal" 
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
                     @click="showCancelModal = false"></div>
                
                <div class="relative bg-white rounded-lg shadow-xl max-w-md w-full p-6"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform scale-90"
                     x-transition:enter-end="opacity-100 transform scale-100">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0 w-12 h-12 rounded-full bg-warning-100 flex items-center justify-center">
                            <svg class="w-6 h-6 text-warning-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        <h3 class="ml-4 text-lg font-semibold text-gray-900">Batalkan Perubahan?</h3>
                    </div>
                    <p class="text-gray-600 mb-6">Anda memiliki perubahan yang belum disimpan. Apakah Anda yakin ingin meninggalkan halaman ini?</p>
                    <div class="flex space-x-3">
                        <button @click="showCancelModal = false" 
                                type="button"
                                class="btn btn-outline flex-1">
                            Tetap di Sini
                        </button>
                        <a href="{{ route('kategori_pelanggan.index') }}" 
                           class="btn btn-danger flex-1 text-center">
                            Ya, Batalkan
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Card -->
        <div class="card">
            <div class="card-header">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900">Informasi Kategori</h2>
                    <span class="badge badge-warning">Mode Edit</span>
                </div>
            </div>
            
            <form x-ref="mainForm" 
                  action="{{ route('kategori_pelanggan.update', $kategori->id) }}" 
                  method="POST" 
                  @submit.prevent="showConfirmModal = true">
                @csrf
                @method('PUT')

                <div class="card-body space-y-6">
                    <!-- Nama Kategori Field -->
                    <div>
                        <label class="form-label">
                            Nama Kategori
                            <span class="text-danger-500">*</span>
                        </label>
                        <input type="text" 
                               name="nama_kategori" 
                               x-ref="namaInput"
                               @input="checkChanges"
                               class="form-input @error('nama_kategori') !border-danger-500 @enderror" 
                               value="{{ old('nama_kategori', $kategori->nama_kategori) }}"
                               placeholder="Contoh: Pelanggan Premium"
                               required>
                        @error('nama_kategori')
                            <p class="form-error">
                                <svg class="inline w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                        <p class="text-sm text-gray-500 mt-1">Masukkan nama kategori pelanggan yang jelas dan mudah dipahami</p>
                    </div>

                    <!-- Changes Indicator -->
                    <div x-show="hasChanges" 
                         x-transition
                         class="alert alert-warning">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-warning-600 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            <div>
                                <p class="font-medium">Perubahan Terdeteksi</p>
                                <p class="text-sm mt-1">Anda memiliki perubahan yang belum disimpan. Jangan lupa untuk menyimpan sebelum meninggalkan halaman.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Info Box -->
                    <div class="alert alert-info">
                        <div class="flex">
                            <svg class="w-5 h-5 text-primary-600 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                            <div>
                                <p class="font-medium">Informasi Data:</p>
                                <ul class="mt-2 text-sm space-y-1">
                                    <li>• ID: <strong>#{{ $kategori->id }}</strong></li>
                                    <li>• Dibuat: {{ $kategori->created_at->format('d M Y, H:i') }}</li>
                                    <li>• Terakhir Diupdate: {{ $kategori->updated_at->format('d M Y, H:i') }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="flex justify-between items-center">
                        <button type="button"
                                @click="hasChanges ? showCancelModal = true : window.location.href = '{{ route('kategori_pelanggan.index') }}'"
                                class="inline-flex items-center text-gray-600 hover:text-gray-900 transition">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Kembali ke Daftar
                        </button>
                        <div class="flex space-x-3">
                            <a href="{{ route('kategori_pelanggan.show', $kategori->id) }}"
                               class="btn btn-outline">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Lihat Detail
                            </a>
                            <button type="submit" 
                                    class="btn btn-success">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Update Data
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-layout>