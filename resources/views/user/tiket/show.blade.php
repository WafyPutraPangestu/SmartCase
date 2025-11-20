<x-layout>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('tiket.index') }}" 
               class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke Daftar Tiket
            </a>
        </div>

        <!-- Header Card -->
        <div class="card mb-6">
            <div class="card-header">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="flex-1">
                        <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $ticket->judul }}</h1>
                        <div class="flex flex-wrap items-center gap-3">
                            <!-- Status Badge -->
                            @php
                                $statusColors = [
                                    'open' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'label' => 'Terbuka'],
                                    'in_progress' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'label' => 'Diproses'],
                                    'resolved' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'label' => 'Selesai'],
                                    'closed' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'label' => 'Ditutup']
                                ];
                                $currentStatus = $statusColors[$ticket->status] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'label' => ucfirst($ticket->status)];
                            @endphp
                            <span class="badge {{ $currentStatus['bg'] }} {{ $currentStatus['text'] }}">
                                {{ $currentStatus['label'] }}
                            </span>

                            <!-- Priority Badge -->
                            @if($ticket->prioritas)
                                @php
                                    $priorityColors = [
                                        'low' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'label' => 'Rendah'],
                                        'medium' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'label' => 'Sedang'],
                                        'high' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-800', 'label' => 'Tinggi'],
                                        'urgent' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'label' => 'Urgent']
                                    ];
                                    $currentPriority = $priorityColors[strtolower($ticket->prioritas)] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'label' => $ticket->prioritas];
                                @endphp
                                <span class="badge {{ $currentPriority['bg'] }} {{ $currentPriority['text'] }} flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                    Prioritas: {{ $currentPriority['label'] }}
                                </span>
                            @endif

                            <!-- AI Badge if priority is set -->
                            @if($ticket->prioritas)
                                <span class="badge bg-purple-100 text-purple-800 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                    </svg>
                                    AI Analyzed
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Edit Button -->
                    <a href="{{ route('tiket.edit', $ticket->id) }}" 
                       class="btn btn-primary inline-flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit Tiket
                    </a>
                </div>
            </div>

            <div class="card-body">
                <!-- Meta Information -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6 pb-6 border-b border-gray-200">
                    <!-- Created Date -->
                    <div class="flex items-start gap-3">
                        <div class="bg-gray-100 p-2 rounded-lg">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Dibuat</p>
                            <p class="font-semibold text-gray-900">{{ $ticket->created_at->format('d M Y') }}</p>
                            <p class="text-xs text-gray-500">{{ $ticket->created_at->format('H:i') }}</p>
                        </div>
                    </div>

                    <!-- Updated Date -->
                    <div class="flex items-start gap-3">
                        <div class="bg-gray-100 p-2 rounded-lg">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Terakhir Update</p>
                            <p class="font-semibold text-gray-900">{{ $ticket->updated_at->diffForHumans() }}</p>
                        </div>
                    </div>

                    <!-- Ticket ID -->
                    <div class="flex items-start gap-3">
                        <div class="bg-gray-100 p-2 rounded-lg">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">ID Tiket</p>
                            <p class="font-semibold text-gray-900 font-mono">#{{ str_pad($ticket->id, 5, '0', STR_PAD_LEFT) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Category Section -->
                <div class="mb-6">
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">Kategori Gangguan</h3>
                    <div class="flex items-center gap-3">
                        <div class="bg-primary-100 p-3 rounded-lg">
                            <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-lg font-semibold text-gray-900">
                                {{ $ticket->kategoriGangguan->nama_gangguan ?? '-' }}
                            </p>
                            @if($ticket->kategoriGangguan && isset($ticket->kategoriGangguan->deskripsi))
                            <p class="text-sm text-gray-600">{{ $ticket->kategoriGangguan->deskripsi }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Description Section -->
                <div>
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">Deskripsi Masalah</h3>
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <p class="text-gray-800 leading-relaxed whitespace-pre-line">{{ $ticket->deskripsi }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Timeline Section (Optional - if you have updates/comments) -->
        @if(isset($ticket->updates) && $ticket->updates->count() > 0)
        <div class="card">
            <div class="card-header">
                <h2 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Riwayat Aktivitas
                </h2>
            </div>
            <div class="card-body">
                <div class="space-y-4">
                    @foreach($ticket->updates as $update)
                    <div class="flex gap-4">
                        <div class="bg-primary-100 p-2 rounded-full h-fit">
                            <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm text-gray-600">{{ $update->created_at->format('d M Y, H:i') }}</p>
                            <p class="text-gray-900">{{ $update->message }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-3 mt-6">
            <a href="{{ route('tiket.index') }}" 
               class="btn btn-outline flex-1 sm:flex-none">
                Kembali
            </a>
            <a href="{{ route('tiket.edit', $ticket->id) }}" 
               class="btn btn-primary flex-1 sm:flex-none">
                Edit Tiket
            </a>
        </div>
    </div>
</x-layout>