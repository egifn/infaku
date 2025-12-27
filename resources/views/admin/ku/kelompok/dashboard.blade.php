@extends('layouts.app')

@section('title', 'Dashboard Kelompok')
@section('page-title', 'Dashboard Kelompok')
@section('icon-page-title', 'bi-house')


@section('content')
<div class="dashboard-container">
    <!-- Welcome Banner -->
    <div class="welcome-banner" id="welcomeBanner">
        <h1>Assalamu'alaikum</h1>
        <p>Selamat datang di Dashboard Kelompok - Kelola keuangan dan aktivitas jamaah dengan mudah</p>
    </div>

    <div>
        <h2>Dashboard</h3>
            <p style="margin-bottom: 12px">{{ $user['nama_kelompok'] }}</p>
    </div>

    <!-- Stats Grid -->
    <div class="stats-grid" id="statsGrid">
        <!-- Skeleton Loading -->
        <div class="stat-card loading-skeleton">
            <div class="stat-header">
                <div class="stat-icon"><i class="bi-graph-up"></i></div>
                <h3 class="stat-title">Total Kas</h3>
            </div>
            <div class="stat-value">Loading...</div>
            <div class="stat-subtitle">Sedang memuat data</div>
        </div>
        <div class="stat-card loading-skeleton">
            <div class="stat-header">
                <div class="stat-icon"><i class="bi-cash-coin"></i></div>
                <h3 class="stat-title">Kas Bulan Ini</h3>
            </div>
            <div class="stat-value">Loading...</div>
            <div class="stat-subtitle">Sedang memuat data</div>
        </div>
        <div class="stat-card loading-skeleton">
            <div class="stat-header">
                <div class="stat-icon"><i class="bi-heart"></i></div>
                <h3 class="stat-title">Total Sodaqoh</h3>
            </div>
            <div class="stat-value">Loading...</div>
            <div class="stat-subtitle">Sedang memuat data</div>
        </div>
        <div class="stat-card loading-skeleton">
            <div class="stat-header">
                <div class="stat-icon"><i class="bi-receipt"></i></div>
                <h3 class="stat-title">Transaksi Bulan Ini</h3>
            </div>
            <div class="stat-value">Loading...</div>
            <div class="stat-subtitle">Sedang memuat data</div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="chart-container">
        <div class="chart-header">
            <h3 class="chart-title">Statistik Kontribusi 6 Bulan Terakhir</h3>
        </div>
        <canvas id="contributionChart" height="100"></canvas>
    </div>

    <!-- Recent Activity -->
    <div class="recent-activity">
        <div class="chart-header">
            <h3 class="chart-title">Aktivitas Transaksi Terbaru</h3>
        </div>
        <div id="recentActivities">
            <!-- Skeleton Loading -->
            <div class="activity-item loading-skeleton">
                <div class="activity-icon"><i class="bi-clock"></i></div>
                <div class="activity-content">
                    <div class="activity-title">Memuat aktivitas...</div>
                    <div class="activity-time">-</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Global variables
        let contributionChart;

        // Load dashboard data
        function loadDashboardData() {
            // Load stats data
            fetch('{{ route('admin.kelompok.api.stats') }}')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        updateStatsGrid(data.stats);
                    } else {
                        showError('Gagal memuat data statistik');
                    }
                })
                .catch(error => {
                    console.error('Error loading stats:', error);
                    showError('Gagal memuat data statistik');
                });

            // Load chart data
            fetch('{{ route('admin.kelompok.api.chart') }}')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        renderContributionChart(data.chart);
                    } else {
                        showError('Gagal memuat data chart');
                    }
                })
                .catch(error => {
                    console.error('Error loading chart:', error);
                    showError('Gagal memuat data chart');
                });

            // Load recent activities
            fetch('{{ route('admin.kelompok.api.activities') }}')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        updateRecentActivities(data.activities);
                    } else {
                        showError('Gagal memuat aktivitas terbaru');
                    }
                })
                .catch(error => {
                    console.error('Error loading activities:', error);
                    showError('Gagal memuat aktivitas terbaru');
                });
        }

        // Update stats grid with real data
        function updateStatsGrid(stats) {
            const statsGrid = document.getElementById('statsGrid');

            const statCards = [{
                    icon: 'bi-graph-up',
                    title: 'Total Kas',
                    value: 'Rp ' + formatNumber(stats.total_kas),
                    subtitle: 'Saldo terkini'
                },
                {
                    icon: 'bi-cash-coin',
                    title: 'Kas Bulan Ini',
                    value: 'Rp ' + formatNumber(stats.kas_bulan_ini),
                    subtitle: 'Pemasukan bulan berjalan'
                },
                {
                    icon: 'bi-heart',
                    title: 'Total Sodaqoh',
                    value: 'Rp ' + formatNumber(stats.total_sodaqoh),
                    subtitle: 'Kumulatif sodaqoh'
                },
                {
                    icon: 'bi-receipt',
                    title: 'Transaksi Bulan Ini',
                    value: stats.transaksi_bulan_ini,
                    subtitle: 'Total transaksi'
                }
            ];

            statsGrid.innerHTML = statCards.map(stat => `
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon"><i class="${stat.icon}"></i></div>
                    <h3 class="stat-title">${stat.title}</h3>
                </div>
                <div class="stat-value">${stat.value}</div>
                <div class="stat-subtitle">${stat.subtitle}</div>
            </div>
        `).join('');
        }

        // Render contribution chart
        function renderContributionChart(chartData) {
            const ctx = document.getElementById('contributionChart').getContext('2d');

            if (contributionChart) {
                contributionChart.destroy();
            }

            contributionChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: chartData.labels,
                    datasets: [{
                        label: 'Jumlah Kontribusi (Rp)',
                        data: chartData.data,
                        backgroundColor: '#105a44',
                        borderColor: '#0d8b66',
                        borderWidth: 1,
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    return 'Rp ' + formatNumber(context.raw);
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function (value) {
                                    return 'Rp ' + formatNumber(value);
                                }
                            }
                        }
                    }
                }
            });
        }

        // Update recent activities
        function updateRecentActivities(activities) {
            const container = document.getElementById('recentActivities');

            if (!activities || activities.length === 0) {
                container.innerHTML = `
                <div class="no-data">
                    <i class="bi-info-circle" style="font-size: 2rem;"></i>
                    <p>Tidak ada aktivitas terbaru</p>
                </div>
            `;
                return;
            }

            container.innerHTML = activities.map(activity => `
            <div class="activity-item">
                <div class="activity-icon">
                    <i class="${getActivityIcon(activity.type)}"></i>
                </div>
                <div class="activity-content">
                    <div class="activity-title">${activity.description}</div>
                    <div class="activity-time">${formatTime(activity.created_at)}</div>
                </div>
            </div>
        `).join('');
        }

        // Helper functions
        function formatNumber(num) {
            return new Intl.NumberFormat('id-ID').format(num);
        }

        function formatTime(timestamp) {
            return new Date(timestamp).toLocaleString('id-ID', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        }

        function getActivityIcon(type) {
            const icons = {
                'transaction': 'bi-arrow-left-right',
                'payment': 'bi-cash',
                'member': 'bi-person',
                'default': 'bi-clock'
            };
            return icons[type] || icons.default;
        }

        function showError(message) {
            // Toast error notification
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
        <span class="toast-icon"><i class="bi-x-circle"></i></span>
        <div class="toast-content">
            <div class="toast-title">Error</div>
            <div class="toast-message">${message}</div>
        </div>
        <button class="toast-close" onclick="this.parentElement.classList.remove('show')">&times;</button>
    `;
            toastContainer.appendChild(toast);
            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => toast.remove(), 500);
            }, 3500);
        }

        // Initialize dashboard when page loads
        document.addEventListener('DOMContentLoaded', function () {
            loadDashboardData();
            // Hide welcome banner after 5 seconds
            setTimeout(function () {
                var banner = document.getElementById('welcomeBanner');
                if (banner) {
                    banner.style.display = 'none';
                }
            }, 5000);
            // Refresh data every 30 seconds
            setInterval(loadDashboardData, 30000);
        });

    </script>
@endpush
