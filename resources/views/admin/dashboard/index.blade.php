<x-layout>
    <!-- CSRF Token Meta -->


    <div x-data="dashboardApp()" x-init="init()" class="min-h-screen bg-gray-50">
        <!-- Header Section -->
        <div class="bg-white border-b border-gray-200 sticky top-0 z-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Dashboard Admin</h1>
                        <p class="mt-1 text-sm text-gray-500">Selamat datang kembali! Berikut ringkasan sistem Anda.</p>
                    </div>

                    <!-- Period Filter -->
                    <div class="flex items-center gap-3">
                        <select x-model="period" @change="loadStats()"
                            class="form-input text-sm py-2 px-3 bg-white border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                            <option value="7">7 Hari Terakhir</option>
                            <option value="30">30 Hari Terakhir</option>
                            <option value="90">90 Hari Terakhir</option>
                        </select>

                        <button @click="loadStats()"
                            class="btn btn-primary px-4 py-2 text-sm shadow-sm hover:shadow-md transition-shadow">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Refresh
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Loading State -->
            <div x-show="loading" class="flex justify-center items-center py-20">
                <div class="animate-spin rounded-full h-12 w-12 border-4 border-primary-500 border-t-transparent"></div>
            </div>

            <!-- Error State -->
            <div x-show="error && !loading" class="alert alert-danger mb-6" x-transition>
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" />
                    </svg>
                    <span x-text="error"></span>
                </div>
            </div>

            <!-- Main Content -->
            <div x-show="!loading" x-transition>
                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <!-- Total Tickets -->
                    <div class="card hover:shadow-lg transition-shadow duration-300">
                        <div class="card-body">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-600">Total Tiket</p>
                                    <p class="text-3xl font-bold text-gray-900 mt-2"
                                        x-text="stats.totals?.tickets || 0"></p>
                                </div>
                                <div class="w-12 h-12 bg-primary-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                </div>
                            </div>
                            <div class="mt-4 flex items-center text-sm">
                                <span class="text-success-600 font-medium"
                                    x-text="'+' + (stats.recent_activity?.new_tickets || 0)"></span>
                                <span class="text-gray-600 ml-2">baru hari ini</span>
                            </div>
                        </div>
                    </div>

                    <!-- Total Users -->
                    <div class="card hover:shadow-lg transition-shadow duration-300">
                        <div class="card-body">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-600">Total User</p>
                                    <p class="text-3xl font-bold text-gray-900 mt-2" x-text="stats.totals?.users || 0">
                                    </p>
                                </div>
                                <div class="w-12 h-12 bg-secondary-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-secondary-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="mt-4 flex items-center text-sm">
                                <span class="text-success-600 font-medium"
                                    x-text="'+' + (stats.recent_activity?.new_users || 0)"></span>
                                <span class="text-gray-600 ml-2">baru hari ini</span>
                            </div>
                        </div>
                    </div>

                    <!-- Avg Response Time -->
                    <div class="card hover:shadow-lg transition-shadow duration-300">
                        <div class="card-body">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-600">Rata-rata Waktu</p>
                                    <p class="text-3xl font-bold text-gray-900 mt-2">
                                        <span x-text="stats.avg_response_time || 0"></span>
                                        <span class="text-lg">jam</span>
                                    </p>
                                </div>
                                <div class="w-12 h-12 bg-warning-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-warning-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="mt-4 text-sm text-gray-600">
                                Waktu penyelesaian
                            </div>
                        </div>
                    </div>

                    <!-- ML Predictions -->
                    <div class="card hover:shadow-lg transition-shadow duration-300">
                        <div class="card-body">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-600">Prediksi ML</p>
                                    <p class="text-3xl font-bold text-gray-900 mt-2"
                                        x-text="stats.ml_stats?.total_predicted || 0"></p>
                                </div>
                                <div class="w-12 h-12 bg-success-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-success-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="mt-4 flex items-center text-sm">
                                <span class="text-gray-600">Confidence: </span>
                                <span class="text-success-600 font-medium ml-1"
                                    x-text="((stats.ml_stats?.avg_confidence || 0) * 100).toFixed(1) + '%'"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CHARTS SECTION - NEW -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                    <!-- Trend Chart -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="text-lg font-semibold text-gray-900">Trend Tiket</h3>
                        </div>
                        <div class="card-body">
                            <canvas id="trendChart" height="250"></canvas>
                        </div>
                    </div>

                    <!-- Status Pie Chart -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="text-lg font-semibold text-gray-900">Distribusi Status</h3>
                        </div>
                        <div class="card-body">
                            <canvas id="statusChart" height="250"></canvas>
                        </div>
                    </div>

                    <!-- Priority Bar Chart -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="text-lg font-semibold text-gray-900">Distribusi Prioritas</h3>
                        </div>
                        <div class="card-body">
                            <canvas id="priorityChart" height="250"></canvas>
                        </div>
                    </div>

                    <!-- Category Doughnut Chart -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="text-lg font-semibold text-gray-900">Kategori Gangguan</h3>
                        </div>
                        <div class="card-body">
                            <canvas id="categoryChart" height="250"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Status & Priority Overview -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                    <!-- Status Distribution -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="text-lg font-semibold text-gray-900">Status Tiket</h3>
                        </div>
                        <div class="card-body">
                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <span class="w-3 h-3 rounded-full bg-warning-500 mr-3"></span>
                                        <span class="text-sm font-medium text-gray-700">Menunggu</span>
                                    </div>
                                    <span class="text-lg font-bold text-gray-900"
                                        x-text="stats.status?.Menunggu || 0"></span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <span class="w-3 h-3 rounded-full bg-primary-500 mr-3"></span>
                                        <span class="text-sm font-medium text-gray-700">Diproses</span>
                                    </div>
                                    <span class="text-lg font-bold text-gray-900"
                                        x-text="stats.status?.Diproses || 0"></span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <span class="w-3 h-3 rounded-full bg-success-500 mr-3"></span>
                                        <span class="text-sm font-medium text-gray-700">Selesai</span>
                                    </div>
                                    <span class="text-lg font-bold text-gray-900"
                                        x-text="stats.status?.Selesai || 0"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Priority Distribution -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="text-lg font-semibold text-gray-900">Prioritas Tiket</h3>
                        </div>
                        <div class="card-body">
                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <span class="w-3 h-3 rounded-full bg-danger-500 mr-3"></span>
                                        <span class="text-sm font-medium text-gray-700">Tinggi</span>
                                    </div>
                                    <span class="text-lg font-bold text-gray-900"
                                        x-text="stats.priority?.Tinggi || 0"></span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <span class="w-3 h-3 rounded-full bg-warning-500 mr-3"></span>
                                        <span class="text-sm font-medium text-gray-700">Sedang</span>
                                    </div>
                                    <span class="text-lg font-bold text-gray-900"
                                        x-text="stats.priority?.Sedang || 0"></span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <span class="w-3 h-3 rounded-full bg-success-500 mr-3"></span>
                                        <span class="text-sm font-medium text-gray-700">Rendah</span>
                                    </div>
                                    <span class="text-lg font-bold text-gray-900"
                                        x-text="stats.priority?.Rendah || 0"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Tickets Table -->
                <div class="card mb-8">
                    <div class="card-header flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">Tiket Terbaru</h3>
                        <a href="/admin/tiket" class="text-sm text-primary-600 hover:text-primary-700 font-medium">
                            Lihat Semua →
                        </a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Kode</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        User</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Judul</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Prioritas</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tanggal</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <template x-for="ticket in stats.recent_tickets" :key="ticket.id">
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-sm font-medium text-gray-900"
                                                x-text="ticket.kode_tiket"></span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-sm text-gray-700" x-text="ticket.user?.name"></span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-sm text-gray-700" x-text="ticket.judul"></span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="badge"
                                                :class="{
                                                    'badge-warning': ticket.status === 'Menunggu',
                                                    'badge-primary': ticket.status === 'Diproses',
                                                    'badge-success': ticket.status === 'Selesai'
                                                }"
                                                x-text="ticket.status"></span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="badge"
                                                :class="{
                                                    'badge-danger': ticket.prioritas === 'Tinggi',
                                                    'badge-warning': ticket.prioritas === 'Sedang',
                                                    'badge-success': ticket.prioritas === 'Rendah'
                                                }"
                                                x-text="ticket.prioritas || '-'"></span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <span x-text="formatDate(ticket.created_at)"></span>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Top Categories & ML Monitoring -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Top Gangguan Categories -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="text-lg font-semibold text-gray-900">Kategori Gangguan Teratas</h3>
                        </div>
                        <div class="card-body">
                            <div class="space-y-3">
                                <template x-for="(item, index) in stats.top_gangguan" :key="index">
                                    <div class="flex items-center justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center justify-between mb-1">
                                                <span class="text-sm font-medium text-gray-700"
                                                    x-text="item.kategori_gangguan_nama"></span>
                                                <span class="text-sm font-bold text-gray-900"
                                                    x-text="item.total"></span>
                                            </div>
                                            <div class="w-full bg-gray-200 rounded-full h-2">
                                                <div class="bg-primary-500 h-2 rounded-full transition-all duration-500"
                                                    :style="`width: ${(item.total / (stats.totals?.tickets || 1) * 100)}%`">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- ML Monitoring (Read-Only) -->
                    <div class="card">
                        <div class="card-header">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-semibold text-gray-900">Monitoring Machine Learning</h3>
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-success-100 text-success-800">
                                    <svg class="w-2 h-2 mr-1" fill="currentColor" viewBox="0 0 8 8">
                                        <circle cx="4" cy="4" r="3" />
                                    </svg>
                                    Auto Retrain Aktif
                                </span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="space-y-4">
                                <!-- Total Predictions -->
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-gray-500 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                        </svg>
                                        <span class="text-sm font-medium text-gray-700">Total Prediksi</span>
                                    </div>
                                    <span class="text-lg font-bold text-gray-900"
                                        x-text="stats.ml_stats?.total_predicted || 0"></span>
                                </div>

                                <!-- Average Confidence -->
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-gray-500 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                        </svg>
                                        <span class="text-sm font-medium text-gray-700">Rata-rata Confidence</span>
                                    </div>
                                    <span class="text-lg font-bold text-gray-900"
                                        x-text="((stats.ml_stats?.avg_confidence || 0) * 100).toFixed(1) + '%'"></span>
                                </div>

                                <!-- High Confidence -->
                                <div
                                    class="flex items-center justify-between p-3 bg-success-50 rounded-lg border border-success-200">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-success-600 mr-2" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span class="text-sm font-medium text-success-700">High Confidence
                                            (≥70%)</span>
                                    </div>
                                    <span class="text-lg font-bold text-success-900"
                                        x-text="stats.ml_stats?.high_confidence || 0"></span>
                                </div>

                                <!-- Low Confidence -->
                                <div
                                    class="flex items-center justify-between p-3 bg-warning-50 rounded-lg border border-warning-200">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-warning-600 mr-2" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                        <span class="text-sm font-medium text-warning-700">Low Confidence (<70%)<
                                                /span>
                                    </div>
                                    <span class="text-lg font-bold text-warning-900"
                                        x-text="stats.ml_stats?.low_confidence || 0"></span>
                                </div>

                                <!-- Info Alert -->
                                <div class="mt-4 p-3 bg-blue-50 rounded-lg border border-blue-200">
                                    <div class="flex items-start">
                                        <svg class="w-5 h-5 text-blue-600 mr-2 flex-shrink-0 mt-0.5" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <div>
                                            <p class="text-xs font-medium text-blue-800">Auto Retrain Enabled</p>
                                            <p class="text-xs text-blue-700 mt-1">Model akan otomatis retrain setiap
                                                500 prediksi untuk meningkatkan akurasi.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js Library -->


    <script>
        function dashboardApp() {
            return {
                loading: true,
                error: null,
                period: '7',
                stats: {
                    totals: {},
                    status: {},
                    priority: {},
                    recent_tickets: [],
                    ml_stats: {},
                    top_gangguan: [],
                    recent_activity: {},
                    trend: []
                },
                charts: {
                    trend: null,
                    status: null,
                    priority: null,
                    category: null
                },

                async init() {
                    await this.loadStats();
                    // Auto refresh every 30 seconds
                    setInterval(() => this.loadStats(), 30000);
                },

                async loadStats() {
                    this.loading = true;
                    this.error = null;

                    try {
                        const response = await fetch(`/api/admin/stats?period=${this.period}`, {
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });

                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }

                        const result = await response.json();

                        if (result.success) {
                            this.stats = result.data;
                            // Update charts after data loaded with proper timing
                            setTimeout(() => {
                                this.renderCharts();
                            }, 200);
                        } else {
                            this.error = result.message || 'Gagal memuat data';
                        }
                    } catch (error) {
                        console.error('Error loading stats:', error);
                        this.error = 'Gagal memuat data dashboard. Silakan coba lagi.';
                    } finally {
                        this.loading = false;
                    }
                },

                renderCharts() {
                    // Wait for DOM to be ready
                    setTimeout(() => {
                        // Destroy existing charts
                        Object.values(this.charts).forEach(chart => {
                            if (chart) chart.destroy();
                        });

                        // 1. Trend Chart (Line)
                        this.renderTrendChart();

                        // 2. Status Chart (Pie)
                        this.renderStatusChart();

                        // 3. Priority Chart (Bar)
                        this.renderPriorityChart();

                        // 4. Category Chart (Doughnut)
                        this.renderCategoryChart();
                    }, 100);
                },

                renderTrendChart() {
                    const ctx = document.getElementById('trendChart');
                    if (!ctx) {
                        console.warn('Trend chart canvas not found');
                        return;
                    }

                    const trendData = this.stats.trend || [];
                    const labels = trendData.map(item => {
                        const date = new Date(item.date);
                        return date.toLocaleDateString('id-ID', {
                            month: 'short',
                            day: 'numeric'
                        });
                    });
                    const data = trendData.map(item => item.total);

                    this.charts.trend = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Jumlah Tiket',
                                data: data,
                                borderColor: 'rgb(59, 130, 246)',
                                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                                tension: 0.4,
                                fill: true,
                                pointRadius: 4,
                                pointHoverRadius: 6,
                                pointBackgroundColor: 'rgb(59, 130, 246)',
                                pointBorderColor: '#fff',
                                pointBorderWidth: 2
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: true,
                                    position: 'top',
                                },
                                tooltip: {
                                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                    padding: 12,
                                    titleFont: {
                                        size: 14
                                    },
                                    bodyFont: {
                                        size: 13
                                    }
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        stepSize: 1
                                    },
                                    grid: {
                                        color: 'rgba(0, 0, 0, 0.05)'
                                    }
                                },
                                x: {
                                    grid: {
                                        display: false
                                    }
                                }
                            }
                        }
                    });
                },

                renderStatusChart() {
                    const ctx = document.getElementById('statusChart');
                    if (!ctx) {
                        console.warn('Status chart canvas not found');
                        return;
                    }

                    const statusData = this.stats.status || {};
                    const labels = Object.keys(statusData);
                    const data = Object.values(statusData);

                    const colors = {
                        'Menunggu': 'rgb(251, 191, 36)',
                        'Diproses': 'rgb(59, 130, 246)',
                        'Selesai': 'rgb(34, 197, 94)'
                    };

                    const backgroundColors = labels.map(label => colors[label] || 'rgb(156, 163, 175)');

                    this.charts.status = new Chart(ctx, {
                        type: 'pie',
                        data: {
                            labels: labels,
                            datasets: [{
                                data: data,
                                backgroundColor: backgroundColors,
                                borderWidth: 2,
                                borderColor: '#fff'
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: {
                                        padding: 15,
                                        font: {
                                            size: 12
                                        }
                                    }
                                },
                                tooltip: {
                                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                    padding: 12,
                                    callbacks: {
                                        label: function(context) {
                                            const label = context.label || '';
                                            const value = context.parsed || 0;
                                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                            const percentage = ((value / total) * 100).toFixed(1);
                                            return `${label}: ${value} (${percentage}%)`;
                                        }
                                    }
                                }
                            }
                        }
                    });
                },

                renderPriorityChart() {
                    const ctx = document.getElementById('priorityChart');
                    if (!ctx) {
                        console.warn('Priority chart canvas not found');
                        return;
                    }

                    const priorityData = this.stats.priority || {};
                    const orderedLabels = ['Tinggi', 'Sedang', 'Rendah'];
                    const labels = orderedLabels.filter(label => priorityData[label] !== undefined);
                    const data = labels.map(label => priorityData[label] || 0);

                    const colors = {
                        'Tinggi': 'rgb(239, 68, 68)',
                        'Sedang': 'rgb(251, 191, 36)',
                        'Rendah': 'rgb(34, 197, 94)'
                    };

                    const backgroundColors = labels.map(label => colors[label] || 'rgb(156, 163, 175)');

                    this.charts.priority = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Jumlah Tiket',
                                data: data,
                                backgroundColor: backgroundColors,
                                borderRadius: 8,
                                borderWidth: 0
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                },
                                tooltip: {
                                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                    padding: 12
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        stepSize: 1
                                    },
                                    grid: {
                                        color: 'rgba(0, 0, 0, 0.05)'
                                    }
                                },
                                x: {
                                    grid: {
                                        display: false
                                    }
                                }
                            }
                        }
                    });
                },

                renderCategoryChart() {
                    const ctx = document.getElementById('categoryChart');
                    if (!ctx) {
                        console.warn('Category chart canvas not found');
                        return;
                    }

                    const categoryData = this.stats.top_gangguan || [];
                    const labels = categoryData.map(item => item.kategori_gangguan_nama);
                    const data = categoryData.map(item => item.total);

                    // Generate random colors for categories
                    const colors = [
                        'rgb(59, 130, 246)',
                        'rgb(16, 185, 129)',
                        'rgb(251, 191, 36)',
                        'rgb(239, 68, 68)',
                        'rgb(168, 85, 247)',
                        'rgb(236, 72, 153)',
                        'rgb(20, 184, 166)',
                        'rgb(251, 146, 60)'
                    ];

                    this.charts.category = new Chart(ctx, {
                        type: 'doughnut',
                        data: {
                            labels: labels,
                            datasets: [{
                                data: data,
                                backgroundColor: colors.slice(0, labels.length),
                                borderWidth: 2,
                                borderColor: '#fff'
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: {
                                        padding: 15,
                                        font: {
                                            size: 11
                                        },
                                        generateLabels: function(chart) {
                                            const data = chart.data;
                                            if (data.labels.length && data.datasets.length) {
                                                return data.labels.map((label, i) => {
                                                    const value = data.datasets[0].data[i];
                                                    return {
                                                        text: label.length > 20 ? label.substring(0,
                                                            20) + '...' : label,
                                                        fillStyle: data.datasets[0].backgroundColor[i],
                                                        hidden: false,
                                                        index: i
                                                    };
                                                });
                                            }
                                            return [];
                                        }
                                    }
                                },
                                tooltip: {
                                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                    padding: 12,
                                    callbacks: {
                                        label: function(context) {
                                            const label = context.label || '';
                                            const value = context.parsed || 0;
                                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                            const percentage = ((value / total) * 100).toFixed(1);
                                            return `${label}: ${value} (${percentage}%)`;
                                        }
                                    }
                                }
                            }
                        }
                    });
                },

                formatDate(dateString) {
                    if (!dateString) return '-';
                    const date = new Date(dateString);
                    return date.toLocaleDateString('id-ID', {
                        day: '2-digit',
                        month: 'short',
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                }
            }
        }
    </script>
</x-layout>
