<!-- resources/views/dashboard/owner.blade.php -->
@extends('layouts.app')

@section('title', 'History Penjualan/Movement')
@section('page-title', 'History Penjualan/Movement')
@section('icon-page-title', 'eg-document')

@push('style')
    <style>
        .welcome-banner {
        background: linear-gradient(to right, #105a44, #1e7d61cc);
        color: white;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        margin-top: 5px;
      }
      
      .stats-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 15px;
        margin: 20px 0;
      }
      
      .stat-card {
        background: white;
        padding: 15px;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        display: flex;
        align-items: center;
        border: 1px solid #bababa;
      }
      
      .stat-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
        font-size: 18px;
        color: white;
        border: 1px solid black;
      }
      
      .stat-content {
        flex: 1;
      }
      
      .stat-value {
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 5px;
      }
      
      .stat-label {
        font-size: 14px;
        color: #666;
      }
      
      .recent-activity {
        margin-top: 30px;
      }
      
      .activity-item {
        display: flex;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px solid #eee;
      }
      
      .activity-icon {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: #bababa;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
        color: #395772;
      }
      
      .activity-content {
        flex: 1;
      }
      
      .activity-title {
        font-weight: 500;
        margin-bottom: 3px;
      }
      
      .activity-time {
        font-size: 12px;
        color: #888;
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
                <div class="stat-value">1,254</div>
                <div class="stat-label">TOTAL KAS</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="eg-user"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">542</div>
                <div class="stat-label">KAS BULAN INI</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="eg-document"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">89%</div>
                <div class="stat-label">TOTAL SODAQOH</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="eg-inbox"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">23</div>
                <div class="stat-label">JAMAAH</div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h2>Aktivitas Terbaru</h2>
        </div>
        <div class="card-body">
            <div class="activity-item">
                <div class="activity-icon">
                    <i class="eg-user"></i>
                </div>
                <div class="activity-content">
                    <div class="activity-title">Informasi</div>
                    <div class="activity-time">dd:mm:yyyy</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>

</script>
@endpush