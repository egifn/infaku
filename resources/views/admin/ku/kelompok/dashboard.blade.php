@extends('layouts.app')

@section('title', 'Dashboard Kelompok')
@section('page-title', 'Dashboard Kelompok')
@section('icon-page-title', 'bi-house')

@push('styles')
    <style>
        .dashboard-container {
            --dk-card: #ffffff;
            --dk-border: #dbe6de;
            --dk-text: #173128;
            --dk-muted: #658073;
            --dk-primary: #105a44;
            --dk-primary-soft: #e6f2ed;
            --dk-accent: #d49e27;
            display: grid;
            gap: 1.25rem;
            color: var(--dk-text);
        }

        .dashboard-hero {
            background: radial-gradient(circle at top right, rgba(212, 158, 39, 0.2), transparent 30%),
                linear-gradient(135deg, #105a44, #1d7a5c);
            color: #fff;
            border-radius: 24px;
            padding: 1.5rem;
            display: grid;
            grid-template-columns: minmax(0, 1.6fr) minmax(260px, 1fr);
            gap: 1rem;
            box-shadow: 0 20px 40px rgba(16, 90, 68, 0.18);
        }

        .hero-eyebrow,
        .hero-meta-label,
        .stat-subtitle,
        .chart-subtitle,
        .activity-time,
        .insight-title,
        .top-meta,
        .empty-chart-text {
            color: var(--dk-muted);
        }

        .hero-eyebrow {
            font-size: 0.85rem;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            margin-bottom: 0.6rem;
            color: rgba(255, 255, 255, 0.8);
        }

        .hero-title {
            margin: 0;
            font-size: clamp(1.5rem, 2vw, 2.2rem);
            line-height: 1.15;
        }

        .hero-subtitle {
            margin: 0.85rem 0 0;
            max-width: 60ch;
            color: rgba(255, 255, 255, 0.84);
            font-size: 12px;
        }

        .hero-meta {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 0.85rem;
        }

        .hero-meta-card,
        .stat-card,
        .insight-card,
        .chart-container,
        .recent-activity {
            background: var(--dk-card);
            border: 1px solid var(--dk-border);
            border-radius: 22px;
            box-shadow: 0 16px 32px rgba(24, 48, 37, 0.06);
        }

        .hero-meta-card {
            background: rgba(255, 255, 255, 0.12);
            border-color: rgba(255, 255, 255, 0.16);
            padding: 1rem;
            backdrop-filter: blur(8px);
        }

        .hero-meta-label {
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.7);
            margin-bottom: 0.35rem;
        }

        .hero-meta-value,
        .stat-value,
        .insight-value,
        .top-value,
        .activity-amount {
            font-weight: 700;
        }

        .hero-meta-value {
            font-size: 1.15rem;
            color: #fff;
        }

        .stats-grid,
        .insight-strip,
        .section-grid,
        .section-grid-2 {
            display: grid;
            gap: 1rem;
        }

        .stats-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        .insight-strip {
            grid-template-columns: repeat(4, minmax(0, 1fr));
        }

        .section-grid {
            grid-template-columns: minmax(0, 1.65fr) minmax(320px, 1fr);
        }

        .section-grid-2 {
            grid-template-columns: minmax(0, 1.1fr) minmax(320px, 0.9fr);
        }

        .stat-card,
        .insight-card,
        .chart-container,
        .recent-activity {
            padding: 1.2rem;
        }

        .stat-card {
            display: grid;
            gap: 0.9rem;
            min-height: 150px;
        }

        .stat-header,
        .chart-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 0.75rem;
        }

        .stat-icon,
        .activity-icon {
            width: 46px;
            height: 46px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 14px;
            background: var(--dk-primary-soft);
            color: var(--dk-primary);
            font-size: 1.2rem;
            flex-shrink: 0;
        }

        .stat-title,
        .chart-title {
            margin: 0;
            font-size: 1rem;
            font-weight: 700;
        }

        .stat-value {
            font-size: clamp(1.3rem, 2vw, 1.9rem);
            line-height: 1.1;
            word-break: break-word;
        }

        .insight-card {
            display: grid;
            gap: 0.7rem;
        }

        .insight-value {
            font-size: 1.35rem;
        }

        .mini-progress {
            height: 8px;
            border-radius: 999px;
            background: #edf3ef;
            overflow: hidden;
        }

        .mini-progress span {
            display: block;
            height: 100%;
            border-radius: inherit;
            background: linear-gradient(90deg, #105a44, #35a379);
        }

        .mini-progress span.warning {
            background: linear-gradient(90deg, #d49e27, #efc468);
        }

        .chart-header {
            margin-bottom: 1rem;
        }

        .chart-canvas-wrap {
            position: relative;
            min-height: 320px;
        }

        .chart-canvas-wrap.compact {
            min-height: 280px;
        }

        .chart-canvas-wrap canvas {
            width: 100% !important;
            height: 100% !important;
        }

        .empty-chart {
            position: absolute;
            inset: 0;
            display: none;
            place-items: center;
            text-align: center;
            padding: 1rem;
            border-radius: 18px;
            background: linear-gradient(180deg, rgba(245, 248, 246, 0.9), rgba(255, 255, 255, 0.95));
        }

        .empty-chart.show {
            display: grid;
        }

        .activity-filter {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-bottom: 0.9rem;
        }

        .chip {
            display: inline-flex;
            align-items: center;
            border-radius: 999px;
            border: 1px solid var(--dk-border);
            padding: 0.35rem 0.8rem;
            font-size: 0.84rem;
            color: var(--dk-muted);
            background: #f7faf8;
        }

        .chip.active {
            background: var(--dk-primary-soft);
            color: var(--dk-primary);
            border-color: rgba(16, 90, 68, 0.15);
        }

        #recentActivities,
        .top-list {
            display: grid;
        }

        .activity-item,
        .top-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.8rem;
            padding: 0.9rem 0;
        }

        .activity-item+.activity-item,
        .top-item+.top-item {
            border-top: 1px solid #edf2ee;
        }

        .activity-main {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            min-width: 0;
        }

        .activity-content,
        .top-label-wrap {
            min-width: 0;
        }

        .activity-title,
        .top-label {
            font-weight: 600;
            color: var(--dk-text);
            word-break: break-word;
        }

        .activity-amount,
        .top-value {
            color: var(--dk-primary);
            text-align: right;
            flex-shrink: 0;
        }

        .loading-skeleton {
            position: relative;
            overflow: hidden;
        }

        .loading-skeleton::after {
            content: "";
            position: absolute;
            inset: 0;
            transform: translateX(-100%);
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.6), transparent);
            animation: dashboard-shimmer 1.4s infinite;
        }

        .no-data {
            display: grid;
            place-items: center;
            gap: 0.6rem;
            text-align: center;
            min-height: 180px;
            color: var(--dk-muted);
        }

        @keyframes dashboard-shimmer {
            100% {
                transform: translateX(100%);
            }
        }

        @media (max-width: 1200px) {

            .stats-grid,
            .insight-strip {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .section-grid,
            .section-grid-2,
            .dashboard-hero {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .dashboard-container {
                gap: 1rem;
            }

            .dashboard-hero,
            .hero-meta-card,
            .stat-card,
            .insight-card,
            .chart-container,
            .recent-activity {
                border-radius: 18px;
            }

            .dashboard-hero {
                padding: 1.15rem;
            }

            .hero-meta,
            .stats-grid,
            .insight-strip {
                grid-template-columns: 1fr;
            }

            .chart-canvas-wrap {
                min-height: 260px;
            }

            .chart-canvas-wrap.compact {
                min-height: 240px;
            }

            .activity-item,
            .top-item {
                align-items: flex-start;
                flex-direction: column;
            }

            .activity-amount,
            .top-value {
                text-align: left;
            }
        }
    </style>
@endpush

@section('content')
    <div class="dashboard-container">
        <section class="dashboard-hero">
            <div>
                <div class="hero-eyebrow">Dashboard Kelompok</div>
                <h1 class="hero-title">Selamat Datang</h1>
                <p class="hero-subtitle">
                    Ringkasan keuangan, aktivitas transaksi, dan kontribusi jamaah ditampilkan dari data transaksi
                    terverifikasi yang tercatat di sistem.
                </p>
            </div>

            <div class="hero-meta">
                <div class="hero-meta-card">
                    <div class="hero-meta-label">Periode Pantauan</div>
                    <div class="hero-meta-value">6 Bulan Terakhir</div>
                </div>
                <div class="hero-meta-card">
                    <div class="hero-meta-label">Status Data</div>
                    <div class="hero-meta-value">Realtime dari transaksi</div>
                </div>
                <div class="hero-meta-card">
                    <div class="hero-meta-label">Wilayah</div>
                    <div class="hero-meta-value">{{ $user['nama_kelompok'] ?? '-' }}</div>
                </div>
                <div class="hero-meta-card">
                    <div class="hero-meta-label">Akses</div>
                    <div class="hero-meta-value">Admin Kelompok</div>
                </div>
            </div>
        </section>

        <section class="stats-grid" id="statsGrid">
            <div class="stat-card loading-skeleton">
                <div class="stat-header">
                    <div class="stat-icon"><i class="bi bi-graph-up-arrow"></i></div>
                    <h3 class="stat-title">Memuat statistik</h3>
                </div>
                <div class="stat-value">...</div>
                <div class="stat-subtitle">Sedang menyiapkan ringkasan dashboard</div>
            </div>
            <div class="stat-card loading-skeleton">
                <div class="stat-header">
                    <div class="stat-icon"><i class="bi bi-wallet2"></i></div>
                    <h3 class="stat-title">Memuat statistik</h3>
                </div>
                <div class="stat-value">...</div>
                <div class="stat-subtitle">Sedang menyiapkan ringkasan dashboard</div>
            </div>
            <div class="stat-card loading-skeleton">
                <div class="stat-header">
                    <div class="stat-icon"><i class="bi bi-people"></i></div>
                    <h3 class="stat-title">Memuat statistik</h3>
                </div>
                <div class="stat-value">...</div>
                <div class="stat-subtitle">Sedang menyiapkan ringkasan dashboard</div>
            </div>
        </section>

        <section class="insight-strip">
            <div class="insight-card">
                <div class="insight-title">Pertumbuhan Bulan Ini</div>
                <div class="insight-value" id="growthValue">0%</div>
                <div class="mini-progress"><span id="growthBar" style="width: 0%;"></span></div>
            </div>
            <div class="insight-card">
                <div class="insight-title">Saldo Bersih</div>
                <div class="insight-value" id="saldoValue">Rp 0</div>
                <div class="mini-progress"><span id="saldoBar" class="warning" style="width: 0%;"></span></div>
            </div>
            <div class="insight-card">
                <div class="insight-title">Tingkat Verifikasi</div>
                <div class="insight-value" id="verifValue">0%</div>
                <div class="mini-progress"><span id="verifBar" style="width: 0%;"></span></div>
            </div>
            <div class="insight-card">
                <div class="insight-title">Rata-rata Harian</div>
                <div class="insight-value" id="avgValue">Rp 0</div>
                <div class="mini-progress"><span id="avgBar" style="width: 0%;"></span></div>
            </div>
        </section>

        <section class="section-grid">
            <div class="chart-container">
                <div class="chart-header">
                    <div>
                        <h3 class="chart-title">Tren Pemasukan 6 Bulan Terakhir</h3>
                        <div class="chart-subtitle">Total transaksi terverifikasi per bulan</div>
                    </div>
                </div>
                <div class="chart-canvas-wrap">
                    <canvas id="contributionChart"></canvas>
                    <div class="empty-chart" id="contributionChartEmpty">
                        <div class="empty-chart-text">Belum ada data pemasukan terverifikasi untuk periode ini.</div>
                    </div>
                </div>
            </div>

            <div class="recent-activity">
                <div class="chart-header">
                    <div>
                        <h3 class="chart-title">Aktivitas Transaksi Terbaru</h3>
                        <div class="chart-subtitle">5 transaksi terverifikasi terbaru</div>
                    </div>
                </div>
                <div class="activity-filter">
                    <span class="chip active">Semua</span>
                    <span class="chip">Terverifikasi</span>
                </div>
                <div id="recentActivities">
                    <div class="activity-item loading-skeleton">
                        <div class="activity-main">
                            <div class="activity-icon"><i class="bi bi-clock-history"></i></div>
                            <div class="activity-content">
                                <div class="activity-title">Memuat aktivitas...</div>
                                <div class="activity-time">-</div>
                            </div>
                        </div>
                        <div class="activity-amount">-</div>
                    </div>
                </div>
            </div>
        </section>

        <section class="section-grid-2">
            <div class="chart-container">
                <div class="chart-header">
                    <div>
                        <h3 class="chart-title">Komposisi Kontribusi</h3>
                        <div class="chart-subtitle">Distribusi kontribusi 30 hari terakhir</div>
                    </div>
                </div>
                <div class="chart-canvas-wrap compact">
                    <canvas id="categoryChart"></canvas>
                    <div class="empty-chart" id="categoryChartEmpty">
                        <div class="empty-chart-text">Belum ada komposisi kontribusi yang bisa ditampilkan.</div>
                    </div>
                </div>
            </div>

            <div class="recent-activity">
                <div class="chart-header">
                    <div>
                        <h3 class="chart-title">Top Kontribusi</h3>
                        <div class="chart-subtitle">Kategori terbesar dalam 30 hari terakhir</div>
                    </div>
                </div>
                <div class="top-list" id="topKontribusiList">
                    <div class="top-item">
                        <div class="top-label-wrap">
                            <div class="top-label">Memuat data...</div>
                            <div class="top-meta">Mohon tunggu</div>
                        </div>
                        <div class="top-value">-</div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        let contributionChart = null;
        let categoryChart = null;

        function loadDashboardData() {
            Promise.all([
                    fetch('{{ route('admin.kelompok.api.stats') }}').then(handleJsonResponse),
                    fetch('{{ route('admin.kelompok.api.chart') }}').then(handleJsonResponse),
                    fetch('{{ route('admin.kelompok.api.activities') }}').then(handleJsonResponse),
                ])
                .then(([statsResponse, chartResponse, activityResponse]) => {
                    if (!statsResponse.success) {
                        throw new Error(statsResponse.message || 'Gagal memuat statistik');
                    }

                    updateStatsGrid(statsResponse.stats || {});
                    updateInsights(statsResponse.stats || {});

                    if (!chartResponse.success) {
                        throw new Error(chartResponse.message || 'Gagal memuat chart');
                    }

                    renderContributionChart(chartResponse.chart || {});
                    renderCategoryChart(chartResponse.chart || {});
                    updateTopKontribusi(chartResponse.chart || {});

                    if (!activityResponse.success) {
                        throw new Error(activityResponse.message || 'Gagal memuat aktivitas');
                    }

                    updateRecentActivities(activityResponse.activities || []);
                })
                .catch(error => {
                    console.error('Dashboard load error:', error);
                    showError(error.message || 'Gagal memuat dashboard');
                });
        }

        function handleJsonResponse(response) {
            if (!response.ok) {
                throw new Error('Terjadi kesalahan saat mengambil data dashboard');
            }

            return response.json();
        }

        function updateStatsGrid(stats) {
            const statsGrid = document.getElementById('statsGrid');
            const cards = [{
                    icon: 'bi bi-wallet2',
                    title: 'Total Kas',
                    value: 'Rp ' + formatNumber(stats.total_kas || 0),
                    subtitle: 'Akumulasi pemasukan terverifikasi'
                },
                {
                    icon: 'bi bi-cash-coin',
                    title: 'Kas Bulan Ini',
                    value: 'Rp ' + formatNumber(stats.kas_bulan_ini || 0),
                    subtitle: 'Pemasukan selama bulan berjalan'
                },
                {
                    icon: 'bi bi-heart',
                    title: 'Total Sodaqoh',
                    value: 'Rp ' + formatNumber(stats.total_sodaqoh || 0),
                    subtitle: 'Akumulasi kategori sodaqoh'
                },
                {
                    icon: 'bi bi-receipt',
                    title: 'Transaksi Bulan Ini',
                    value: formatNumber(stats.transaksi_bulan_ini || 0),
                    subtitle: 'Total transaksi terverifikasi'
                },
                {
                    icon: 'bi bi-people',
                    title: 'Jamaah Aktif',
                    value: formatNumber(stats.total_jamaah || 0),
                    subtitle: 'Jumlah jamaah aktif dalam kelompok'
                },
                {
                    icon: 'bi bi-house-heart',
                    title: 'Total Keluarga',
                    value: formatNumber(stats.total_keluarga || 0),
                    subtitle: 'Keluarga yang terdaftar di kelompok'
                }
            ];

            statsGrid.innerHTML = cards.map(card => `
                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon"><i class="${card.icon}"></i></div>
                        <h3 class="stat-title">${card.title}</h3>
                    </div>
                    <div class="stat-value">${card.value}</div>
                    <div class="stat-subtitle">${card.subtitle}</div>
                </div>
            `).join('');
        }

        function updateInsights(stats) {
            const growth = normalizePercent(stats.growth_bulan_ini || 0);
            const verification = normalizePercent(stats.verifikasi_rate || 0);
            const avgDaily = stats.rata_harian_bulan_ini || 0;
            const saldoProgress = normalizePercent(stats.saldo_progress || 0);
            const avgProgress = normalizePercent(stats.avg_harian_progress || 0);

            document.getElementById('growthValue').textContent = formatSignedPercent(stats.growth_bulan_ini || 0);
            document.getElementById('growthBar').style.width = `${growth}%`;

            document.getElementById('saldoValue').textContent = 'Rp ' + formatNumber(stats.total_kas || 0);
            document.getElementById('saldoBar').style.width = `${saldoProgress}%`;

            document.getElementById('verifValue').textContent = `${verification}%`;
            document.getElementById('verifBar').style.width = `${verification}%`;

            document.getElementById('avgValue').textContent = 'Rp ' + formatNumber(avgDaily);
            document.getElementById('avgBar').style.width = `${avgProgress}%`;
        }

        function renderContributionChart(chartData) {
            const monthly = chartData.monthly || {};
            const labels = Array.isArray(monthly.labels) ? monthly.labels : [];
            const totals = Array.isArray(monthly.totals) ? monthly.totals.map(Number) : [];
            const average = Number(monthly.average || 0);
            const hasData = totals.some(value => value > 0);

            toggleEmptyChart('contributionChartEmpty', !hasData);

            if (contributionChart) {
                contributionChart.destroy();
            }

            if (!window.Chart) {
                showError('Library chart tidak berhasil dimuat');
                return;
            }

            contributionChart = new Chart(document.getElementById('contributionChart'), {
                type: 'line',
                data: {
                    labels,
                    datasets: [{
                            label: 'Pemasukan',
                            data: totals,
                            borderColor: '#105a44',
                            backgroundColor: 'rgba(16, 90, 68, 0.14)',
                            fill: true,
                            tension: 0.35,
                            pointRadius: 4,
                            pointHoverRadius: 6
                        },
                        {
                            label: 'Rata-rata',
                            data: labels.map(() => average),
                            borderColor: '#d49e27',
                            borderDash: [6, 6],
                            pointRadius: 0,
                            fill: false,
                            tension: 0
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false
                    },
                    plugins: {
                        legend: {
                            position: 'bottom'
                        },
                        tooltip: {
                            callbacks: {
                                label(context) {
                                    return `${context.dataset.label}: Rp ${formatNumber(context.raw || 0)}`;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            }
                        },
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback(value) {
                                    return 'Rp ' + formatCompactCurrency(value);
                                }
                            }
                        }
                    }
                }
            });
        }

        function renderCategoryChart(chartData) {
            const categories = chartData.categories || {};
            const labels = Array.isArray(categories.labels) ? categories.labels : [];
            const totals = Array.isArray(categories.totals) ? categories.totals.map(Number) : [];
            const hasData = totals.some(value => value > 0);

            toggleEmptyChart('categoryChartEmpty', !hasData);

            if (categoryChart) {
                categoryChart.destroy();
            }

            if (!window.Chart) {
                return;
            }

            categoryChart = new Chart(document.getElementById('categoryChart'), {
                type: 'doughnut',
                data: {
                    labels,
                    datasets: [{
                        data: totals,
                        backgroundColor: ['#105a44', '#1f7b5c', '#31a27a', '#6ec7a0', '#d49e27', '#9fb4a8'],
                        borderWidth: 0,
                        hoverOffset: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 12,
                                boxHeight: 12
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label(context) {
                                    return `${context.label}: Rp ${formatNumber(context.raw || 0)}`;
                                }
                            }
                        }
                    },
                    cutout: '62%'
                }
            });
        }

        function updateTopKontribusi(chartData) {
            const list = document.getElementById('topKontribusiList');
            const categories = chartData.categories || {};
            const items = Array.isArray(categories.items) ? categories.items : [];

            if (!items.length) {
                list.innerHTML = `
                    <div class="top-item">
                        <div class="top-label-wrap">
                            <div class="top-label">Belum ada data kontribusi</div>
                            <div class="top-meta">Data top kontribusi akan muncul setelah ada transaksi.</div>
                        </div>
                        <div class="top-value">-</div>
                    </div>
                `;
                return;
            }

            list.innerHTML = items.slice(0, 5).map((item, index) => `
                <div class="top-item">
                    <div class="top-label-wrap">
                        <div class="top-label">${index + 1}. ${escapeHtml(item.label || '-')}</div>
                        <div class="top-meta">${formatNumber(item.transactions || 0)} transaksi</div>
                    </div>
                    <div class="top-value">Rp ${formatNumber(item.total || 0)}</div>
                </div>
            `).join('');
        }

        function updateRecentActivities(activities) {
            const container = document.getElementById('recentActivities');

            if (!Array.isArray(activities) || activities.length === 0) {
                container.innerHTML = `
                    <div class="no-data">
                        <i class="bi bi-info-circle"></i>
                        <div>Tidak ada aktivitas terbaru</div>
                    </div>
                `;
                return;
            }

            container.innerHTML = activities.map(activity => `
                <div class="activity-item">
                    <div class="activity-main">
                        <div class="activity-icon">
                            <i class="${getActivityIcon(activity.type)}"></i>
                        </div>
                        <div class="activity-content">
                            <div class="activity-title">${escapeHtml(activity.description || '-')}</div>
                            <div class="activity-time">${escapeHtml(formatTime(activity.created_at))}</div>
                        </div>
                    </div>
                    <div class="activity-amount">Rp ${formatNumber(activity.amount || 0)}</div>
                </div>
            `).join('');
        }

        function toggleEmptyChart(elementId, isVisible) {
            const element = document.getElementById(elementId);
            if (element) {
                element.classList.toggle('show', Boolean(isVisible));
            }
        }

        function formatNumber(value) {
            return new Intl.NumberFormat('id-ID').format(Number(value) || 0);
        }

        function formatCompactCurrency(value) {
            return new Intl.NumberFormat('id-ID', {
                notation: 'compact',
                maximumFractionDigits: 1
            }).format(Number(value) || 0);
        }

        function formatTime(timestamp) {
            if (!timestamp) {
                return '-';
            }

            const date = new Date(timestamp);
            if (Number.isNaN(date.getTime())) {
                return timestamp;
            }

            return date.toLocaleString('id-ID', {
                day: '2-digit',
                month: 'short',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        }

        function normalizePercent(value) {
            return Math.max(0, Math.min(100, Math.round(Number(value) || 0)));
        }

        function formatSignedPercent(value) {
            const numericValue = Number(value) || 0;
            const prefix = numericValue > 0 ? '+' : '';
            return `${prefix}${Math.round(numericValue)}%`;
        }

        function getActivityIcon(type) {
            const icons = {
                payment: 'bi bi-cash-stack',
                transaction: 'bi bi-arrow-left-right',
                info: 'bi bi-info-circle',
                default: 'bi bi-clock-history'
            };

            return icons[type] || icons.default;
        }

        function escapeHtml(value) {
            return String(value ?? '')
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        function showError(message) {
            let toastContainer = document.getElementById('toastContainer');
            if (!toastContainer) {
                toastContainer = document.createElement('div');
                toastContainer.id = 'toastContainer';
                toastContainer.className = 'toast-container';
                document.body.appendChild(toastContainer);
            }

            const toast = document.createElement('div');
            toast.className = 'toast error show';
            toast.innerHTML = `
                <span class="toast-icon"><i class="bi bi-x-circle"></i></span>
                <div class="toast-content">
                    <div class="toast-title">Error</div>
                    <div class="toast-message">${escapeHtml(message)}</div>
                </div>
                <button class="toast-close" onclick="this.parentElement.classList.remove('show')">&times;</button>
            `;

            toastContainer.appendChild(toast);
            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => toast.remove(), 500);
            }, 3500);
        }

        document.addEventListener('DOMContentLoaded', () => {
            loadDashboardData();
            setInterval(loadDashboardData, 30000);
        });
    </script>
@endpush
