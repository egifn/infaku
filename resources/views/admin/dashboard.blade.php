<!-- resources/views/dashboard/owner.blade.php -->
@extends('layouts.app')

@section('title', 'Dashboard Kelompok')
@section('page-title', 'Dashboard Kelompok')
@section('icon-page-title', 'eg-document')

@push('style')
    <style>
        :root {
            --dash-green: #105a44;
            --dash-green-2: #1b7a5d;
            --dash-ink: #1f2a2e;
            --dash-muted: #6b7c7a;
            --dash-bg: #f6f8f7;
            --dash-card: #ffffff;
            --dash-border: #e4e9e7;
            --dash-accent: #f0b429;
        }

        .dashboard-container {
            background: var(--dash-bg);
            border-radius: 10px;
            padding: 10px;
        }

        .welcome-banner {
            background: linear-gradient(120deg, var(--dash-green), var(--dash-green-2));
            color: white;
            padding: 24px;
            border-radius: 10px;
            margin-bottom: 18px;
            margin-top: 5px;
            position: relative;
            overflow: hidden;
        }

        .welcome-banner::after {
            content: '';
            position: absolute;
            right: -60px;
            top: -40px;
            width: 180px;
            height: 180px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.12);
        }

        .welcome-banner h1 {
            font-size: 20px;
            margin-bottom: 4px;
        }

        .welcome-banner p {
            font-size: 12px;
            opacity: 0.9;
        }

        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 12px;
            margin: 14px 0 18px;
        }

        .stat-card {
            background: var(--dash-card);
            padding: 16px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            border: 1px solid var(--dash-border);
            box-shadow: 0 6px 14px rgba(16, 90, 68, 0.06);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 18px rgba(16, 90, 68, 0.12);
        }

        .stat-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 18px;
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.2);
            background: linear-gradient(135deg, var(--dash-green), var(--dash-green-2));
        }

        .stat-content {
            flex: 1;
        }

        .stat-value {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 5px;
            color: var(--dash-ink);
        }

        .stat-label {
            font-size: 11px;
            color: var(--dash-muted);
            letter-spacing: 0.4px;
        }

        .section-grid {
            display: grid;
            grid-template-columns: 1.2fr 0.8fr;
            gap: 12px;
            margin-top: 10px;
        }

        .section-grid-2 {
            display: grid;
            grid-template-columns: 0.9fr 1.1fr;
            gap: 12px;
            margin-top: 12px;
        }

        .card {
            border: 1px solid var(--dash-border);
            border-radius: 10px;
            box-shadow: 0 6px 14px rgba(16, 90, 68, 0.06);
        }

        .card-header h2,
        .card-header h3 {
            font-size: 14px;
        }

        .chart-wrap {
            padding: 10px 0 0;
        }

        .chart-title {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 12px;
            color: var(--dash-muted);
        }

        .chart-legend {
            display: flex;
            gap: 12px;
            font-size: 11px;
            color: var(--dash-muted);
            margin-top: 8px;
        }

        .legend-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 6px;
        }

        .activity-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .activity-filter {
            display: flex;
            gap: 8px;
            margin-bottom: 10px;
        }

        .chip {
            padding: 4px 8px;
            border-radius: 999px;
            font-size: 10px;
            background: #eef4f2;
            color: var(--dash-green);
            border: 1px solid var(--dash-border);
        }

        .activity-item {
            display: flex;
            align-items: flex-start;
            padding: 10px;
            border: 1px solid var(--dash-border);
            border-radius: 8px;
            background: #fff;
            gap: 10px;
        }

        .activity-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: #e8f2ef;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--dash-green);
            flex-shrink: 0;
        }

        .activity-content {
            flex: 1;
        }

        .activity-title {
            font-weight: 500;
            margin-bottom: 3px;
            color: var(--dash-ink);
            font-size: 12px;
        }

        .activity-time {
            font-size: 12px;
            color: var(--dash-muted);
        }

        .activity-meta {
            font-size: 11px;
            color: var(--dash-muted);
        }

        .kpi-strip {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin-top: 12px;
        }

        .kpi-item {
            background: #fff;
            border: 1px solid var(--dash-border);
            border-radius: 8px;
            padding: 10px;
        }

        .kpi-title {
            font-size: 11px;
            color: var(--dash-muted);
        }

        .kpi-value {
            font-size: 14px;
            font-weight: 600;
            color: var(--dash-ink);
        }

        .insight-strip {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            margin-top: 12px;
        }

        .insight {
            background: #fff;
            border: 1px solid var(--dash-border);
            border-radius: 10px;
            padding: 10px;
        }

        .insight-title {
            font-size: 11px;
            color: var(--dash-muted);
        }

        .insight-value {
            font-size: 14px;
            font-weight: 600;
            color: var(--dash-ink);
        }

        .mini-progress {
            height: 6px;
            border-radius: 999px;
            background: #edf2f1;
            overflow: hidden;
            margin-top: 6px;
        }

        .mini-progress > span {
            display: block;
            height: 100%;
            background: linear-gradient(90deg, var(--dash-green), var(--dash-green-2));
            width: 60%;
        }

        .top-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .top-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 8px 10px;
            border: 1px solid var(--dash-border);
            border-radius: 8px;
            background: #fff;
        }

        .top-label {
            font-size: 12px;
            color: var(--dash-ink);
        }

        .top-value {
            font-size: 12px;
            color: var(--dash-muted);
        }

        @media (max-width: 900px) {
            .section-grid {
                grid-template-columns: 1fr;
            }

            .section-grid-2 {
                grid-template-columns: 1fr;
            }

            .insight-strip {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
@endpush

@section('content')
    <div class="dashboard-container">
        <div class="welcome-banner">
            <h1>Selamat Datang di Dashboard</h1>
            <p>Kelola dan pantau aktivitas bisnis Anda dari sini</p>
        </div>

        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="eg-chart"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">Rp {{ number_format($total_kontribusi ?? 0, 0, ',', '.') }}</div>
                    <div class="stat-label">TOTAL KAS</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="eg-user"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">Rp {{ number_format($kas_bulan_ini ?? 0, 0, ',', '.') }}</div>
                    <div class="stat-label">KAS BULAN INI</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="eg-document"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">Rp {{ number_format($total_sodaqoh ?? 0, 0, ',', '.') }}</div>
                    <div class="stat-label">TOTAL SODAQOH</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="eg-inbox"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $total_jamaah ?? ($total_keluarga ?? 0) }}</div>
                    <div class="stat-label">JAMAAH</div>
                </div>
            </div>
        </div>

        <div class="insight-strip">
            <div class="insight">
                <div class="insight-title">Pertumbuhan Bulan Ini</div>
                <div class="insight-value">+{{ $growth_kontribusi ?? 0 }}%</div>
                <div class="mini-progress"><span style="width: {{ min(100, max(0, $growth_kontribusi ?? 0)) }}%;"></span></div>
            </div>
            <div class="insight">
                <div class="insight-title">Kontribusi Tertinggi</div>
                <div class="insight-value">Rp {{ number_format($top_kontribusi ?? 0, 0, ',', '.') }}</div>
                <div class="mini-progress"><span style="width: 72%;"></span></div>
            </div>
            <div class="insight">
                <div class="insight-title">Tingkat Verifikasi</div>
                <div class="insight-value">{{ $verifikasi_rate ?? 0 }}%</div>
                <div class="mini-progress"><span style="width: {{ min(100, max(0, $verifikasi_rate ?? 0)) }}%;"></span></div>
            </div>
            <div class="insight">
                <div class="insight-title">Saldo Bersih</div>
                <div class="insight-value">Rp {{ number_format($saldo_bersih ?? 0, 0, ',', '.') }}</div>
                <div class="mini-progress"><span style="width: 58%;"></span></div>
            </div>
        </div>

        <div class="section-grid">
            <div class="card">
                <div class="card-header">
                    <h3>Ringkasan Kontribusi</h3>
                </div>
                <div class="card-body">
                    <div class="chart-title">
                        <span>Tren 6 Periode Terakhir</span>
                        <span>Target vs Realisasi</span>
                    </div>
                    <div class="chart-wrap">
                        <canvas id="kontribusiChart" height="140"></canvas>
                        <div class="chart-legend">
                            <div><span class="legend-dot" style="background:#105a44;"></span>Pemasukan</div>
                            <div><span class="legend-dot" style="background:#f0b429;"></span>Target</div>
                        </div>
                    </div>
                    <div class="kpi-strip">
                        <div class="kpi-item">
                            <div class="kpi-title">Rata-rata Harian</div>
                            <div class="kpi-value">Rp {{ number_format(($total_kontribusi ?? 0) / 30, 0, ',', '.') }}</div>
                        </div>
                        <div class="kpi-item">
                            <div class="kpi-title">Transaksi</div>
                            <div class="kpi-value">{{ $total_transaksi ?? 0 }}</div>
                        </div>
                        <div class="kpi-item">
                            <div class="kpi-title">Jamaah Aktif</div>
                            <div class="kpi-value">{{ $total_jamaah ?? ($total_keluarga ?? 0) }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3>Aktivitas Terbaru</h3>
                </div>
                <div class="card-body">
                    <div class="activity-filter">
                        <span class="chip">Semua</span>
                        <span class="chip">Kontribusi</span>
                        <span class="chip">Jamaah</span>
                    </div>
                    <div class="activity-list">
                        <div class="activity-item">
                            <div class="activity-icon">
                                <i class="eg-document"></i>
                            </div>
                            <div class="activity-content">
                                <div class="activity-title">Laporan Keuangan dibuat</div>
                                <div class="activity-meta">Laporan Bulanan</div>
                                <div class="activity-time">Hari ini</div>
                            </div>
                        </div>
                        <div class="activity-item">
                            <div class="activity-icon">
                                <i class="eg-user"></i>
                            </div>
                            <div class="activity-content">
                                <div class="activity-title">Jamaah baru ditambahkan</div>
                                <div class="activity-meta">Data jamaah masuk</div>
                                <div class="activity-time">Kemarin</div>
                            </div>
                        </div>
                        <div class="activity-item">
                            <div class="activity-icon">
                                <i class="eg-inbox"></i>
                            </div>
                            <div class="activity-content">
                                <div class="activity-title">Kontribusi terverifikasi</div>
                                <div class="activity-meta">Kas Bulanan</div>
                                <div class="activity-time">2 hari lalu</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="section-grid-2">
            <div class="card">
                <div class="card-header">
                    <h3>Komposisi Kontribusi</h3>
                </div>
                <div class="card-body">
                    <div class="chart-title">
                        <span>Distribusi per Kategori</span>
                        <span>6 kategori utama</span>
                    </div>
                    <canvas id="kategoriChart" height="160"></canvas>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3>Top Kontribusi</h3>
                </div>
                <div class="card-body">
                    <div class="top-list">
                        <div class="top-item">
                            <div class="top-label">Infaq Bulanan</div>
                            <div class="top-value">Rp {{ number_format($top_kategori_1 ?? 0, 0, ',', '.') }}</div>
                        </div>
                        <div class="top-item">
                            <div class="top-label">Zakat</div>
                            <div class="top-value">Rp {{ number_format($top_kategori_2 ?? 0, 0, ',', '.') }}</div>
                        </div>
                        <div class="top-item">
                            <div class="top-label">Sodaqoh</div>
                            <div class="top-value">Rp {{ number_format($top_kategori_3 ?? 0, 0, ',', '.') }}</div>
                        </div>
                        <div class="top-item">
                            <div class="top-label">Wakaf</div>
                            <div class="top-value">Rp {{ number_format($top_kategori_4 ?? 0, 0, ',', '.') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-stat-grid">
            <!-- Card untuk Admin Pusat, Daerah, Desa -->
            @if (in_array(Auth::user()->role_id, ['RL001', 'RL002', 'RL003']))
                <div class="card-status-item">
                    <div class="card-status-content">
                        <h2>Total
                            {{ Auth::user()->role_id == 'RL001' ? 'Daerah' : (Auth::user()->role_id == 'RL002' ? 'Desa' : 'Kelompok') }}
                        </h2>
                        <div class="stat-value">{{ $total_daerah ?? ($total_desa ?? ($total_kelompok ?? 0)) }}</div>
                    </div>
                </div>
            @endif

            <!-- Card untuk semua role kecuali Ruyah -->
            @if (Auth::user()->role_id != 'RL005')
                <div class="card-status-item">
                    <div class="card-status-content">
                        <h2>Total Jamaah</h2>
                        <div class="stat-value">{{ $total_jamaah ?? ($total_keluarga ?? 0) }}</div>
                    </div>
                </div>
            @endif

            <div class="card-status-item">
                <div class="card-status-content">
                    <h2>Total Kontribusi</h2>
                    <div class="stat-value">Rp {{ number_format($total_kontribusi ?? 0, 0, ',', '.') }}</div>
                    <div class="status-change positive">Bulan ini</div>
                </div>
            </div>

            <!-- Card khusus untuk Ruyah -->
            @if (Auth::user()->role_id == 'RL005')
                <div class="card-status-item">
                    <div class="card-status-content">
                        <h2>Total Transaksi</h2>
                        <div class="stat-value">{{ $total_transaksi ?? 0 }}</div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('kontribusiChart');
        if (ctx) {
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['M1', 'M2', 'M3', 'M4', 'M5', 'M6'],
                    datasets: [{
                            label: 'Pemasukan',
                            data: [12, 19, 14, 22, 18, 26],
                            borderColor: '#105a44',
                            backgroundColor: 'rgba(16, 90, 68, 0.12)',
                            tension: 0.4,
                            fill: true,
                            pointRadius: 3
                        },
                        {
                            label: 'Target',
                            data: [15, 20, 18, 24, 22, 28],
                            borderColor: '#f0b429',
                            backgroundColor: 'rgba(240, 180, 41, 0.08)',
                            tension: 0.4,
                            fill: false,
                            borderDash: [6, 4],
                            pointRadius: 0
                        }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            grid: {
                                color: '#eef2f0'
                            },
                            ticks: {
                                color: '#6b7c7a',
                                font: {
                                    size: 10
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: '#6b7c7a',
                                font: {
                                    size: 10
                                }
                            }
                        }
                    }
                }
            });
        }

        const kategoriCtx = document.getElementById('kategoriChart');
        if (kategoriCtx) {
            new Chart(kategoriCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Infaq', 'Zakat', 'Sodaqoh', 'Wakaf', 'Lainnya', 'Kas'],
                    datasets: [{
                        data: [30, 18, 16, 12, 14, 10],
                        backgroundColor: [
                            '#105a44',
                            '#1b7a5d',
                            '#2ea37c',
                            '#7ec6a7',
                            '#f0b429',
                            '#95a7a3'
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 10,
                                boxHeight: 10,
                                font: {
                                    size: 10
                                }
                            }
                        }
                    },
                    cutout: '60%'
                }
            });
        }
    </script>
@endpush
