<x-layout>
    <div x-data="profileForm()" class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Back Button -->
        <div class="mb-6">
            <a href="/" 
               class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali
            </a>
        </div>

        <!-- Profile Card -->
        <div class="card">
            <!-- Header -->
            <div class="card-header">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <!-- Avatar -->
                        {{-- <div class="w-16 h-16 bg-gradient-primary rounded-full flex items-center justify-center text-white text-2xl font-bold shadow-md">
                            <span x-text="name.charAt(0).toUpperCase()"></span>
                        </div> --}}
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                                Profile Saya
                                <!-- Lock Icon -->
                                <svg x-show="isLocked" class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                                <svg x-show="!isLocked" class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"/>
                                </svg>
                            </h1>
                            <p class="text-gray-600 text-sm">Kelola informasi profil Anda</p>
                        </div>
                    </div>

                    <!-- Toggle Edit Button -->
                    <button @click="toggleLock" 
                            class="btn flex items-center gap-2 justify-center sm:justify-start"
                            :class="isLocked ? 'btn-primary' : 'btn-outline'">
                        <svg x-show="isLocked" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        <svg x-show="!isLocked" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        <span x-text="isLocked ? 'Edit Profile' : 'Batal'"></span>
                    </button>
                </div>
            </div>

            <!-- Form Body -->
            <div class="card-body">
                <div class="relative">
                    <!-- Lock Overlay -->
                    <div x-show="isLocked" 
                         class="absolute inset-0  z-10 rounded-lg cursor-not-allowed"
                         title="Klik 'Edit Profile' untuk mengubah data">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nama -->
                        <div>
                            <label class="form-label flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                Nama Lengkap
                                <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                :disabled="isLocked"
                                class="form-input"
                                :class="{ 'border-red-300': errors.name }"
                                x-model="name"
                                placeholder="Masukkan nama lengkap"
                            >
                            <p x-show="errors.name" x-text="errors.name" class="form-error"></p>
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="form-label flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                Email
                                <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="email" 
                                :disabled="isLocked"
                                class="form-input"
                                :class="{ 'border-red-300': errors.email }"
                                x-model="email"
                                placeholder="email@example.com"
                            >
                            <p x-show="errors.email" x-text="errors.email" class="form-error"></p>
                        </div>

                        <!-- No Telepon -->
                        <div>
                            <label class="form-label flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                                No Telepon
                            </label>
                            <input 
                                type="text" 
                                :disabled="isLocked"
                                class="form-input"
                                x-model="no_telepon"
                                placeholder="08xx-xxxx-xxxx"
                            >
                        </div>

                        <!-- Kategori Pelanggan -->
                        <div>
                            <label class="form-label flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                </svg>
                                Kategori Pelanggan
                            </label>
                            <select 
                                :disabled="isLocked"
                                class="form-input"
                                x-model="kategori_pelanggan_id">
                                <option value="">-- Pilih Kategori --</option>
                                @foreach ($kategoriPelanggan as $kp)
                                    <option value="{{ $kp->id }}">{{ $kp->nama_kategori }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Alamat (Full Width) -->
                        <div class="md:col-span-2">
                            <label class="form-label flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                Alamat
                            </label>
                            <textarea 
                                rows="3" 
                                :disabled="isLocked"
                                class="form-input"
                                x-model="alamat"
                                placeholder="Masukkan alamat lengkap Anda"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Actions -->
            <div class="card-footer" x-show="!isLocked" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 transform -translate-y-2"
                 x-transition:enter-end="opacity-100 transform translate-y-0"
                 style="display: none;">
                <div class="flex flex-col sm:flex-row gap-3 justify-end">
                    <button @click="toggleLock" 
                            class="btn btn-outline">
                        Batal
                    </button>
                    <button @click="saveProfile" 
                            :disabled="isLoading"
                            class="btn btn-primary">
                        <svg x-show="!isLoading" class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <svg x-show="isLoading" class="w-5 h-5 inline mr-2 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span x-text="isLoading ? 'Menyimpan...' : 'Simpan Perubahan'"></span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Status Messages -->
        <div x-show="statusMessage" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform translate-y-2"
             x-transition:enter-end="opacity-100 transform translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="mt-4"
             style="display: none;">
            <div class="alert"
                 :class="statusType === 'success' ? 'alert-success' : 'alert-danger'">
                <div class="flex items-center gap-2">
                    <svg x-show="statusType === 'success'" class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <svg x-show="statusType === 'error'" class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span x-text="statusMessage"></span>
                </div>
            </div>
        </div>

        <!-- Info Box -->
        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div class="text-sm text-blue-800">
                    <p class="font-semibold mb-1">Informasi Penting</p>
                    <ul class="list-disc list-inside space-y-1 text-blue-700">
                        <li>Data profile digunakan untuk identifikasi tiket support</li>
                        <li>Kategori pelanggan mempengaruhi prioritas penanganan tiket</li>
                        <li>Pastikan email dan nomor telepon aktif untuk notifikasi</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script>
    function profileForm() {
        return {
            name: @js($user->name),
            email: @js($user->email),
            alamat: @js($profile?->alamat ?? ''),
            no_telepon: @js($profile?->no_telepon ?? ''),
            kategori_pelanggan_id: @js($profile?->kategori_pelanggan_id ?? ''),
            
            isLoading: false,
            statusMessage: '',
            statusType: 'success',
            errors: {},
            isLocked: true,

            toggleLock() {
                this.isLocked = !this.isLocked;
                this.statusMessage = '';
                this.errors = {};
            },

            saveProfile() {
                const tokenMeta = document.querySelector('meta[name="csrf-token"]');
                if (!tokenMeta) {
                    this.showStatus('Error: CSRF Token tidak ditemukan', 'error');
                    return;
                }
                const token = tokenMeta.getAttribute('content');

                this.isLoading = true;
                this.statusMessage = '';
                this.errors = {};

                fetch("{{ route('profile.update') }}", { 
                    method: "PUT",
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json",
                        "X-CSRF-TOKEN": token
                    },
                    body: JSON.stringify({
                        name: this.name,
                        email: this.email,
                        alamat: this.alamat,
                        no_telepon: this.no_telepon,
                        kategori_pelanggan_id: this.kategori_pelanggan_id
                    })
                })
                .then(async response => {
                    const data = await response.json();
                    if (!response.ok) throw data;
                    return data;
                })
                .then(data => {
                    this.showStatus('Profile berhasil diperbarui!', 'success');
                    this.isLocked = true; 
                })
                .catch(err => {
                    let msg = 'Gagal menyimpan perubahan';
                    if (err.errors) {
                        this.errors = err.errors;
                        msg = 'Periksa kembali data yang Anda masukkan';
                    } else if (err.message) {
                        msg = err.message;
                    }
                    this.showStatus(msg, 'error');
                })
                .finally(() => {
                    this.isLoading = false;
                });
            },

            showStatus(message, type) {
                this.statusMessage = message;
                this.statusType = type;
                if (type === 'success') {
                    setTimeout(() => { 
                        this.statusMessage = ''; 
                    }, 5000);
                }
            }
        }
    }
    </script>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</x-layout>