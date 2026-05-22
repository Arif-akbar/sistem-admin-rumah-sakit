@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Ringkasan Operasional</h1>
        <p class="metric-label">Selasa, 22 Mei 2026</p>
    </div>
    <div style="display: flex; gap: 12px;">
        <div class="input-field" style="display: flex; align-items: center; gap: 8px; padding: 8px 12px; background: white;">
            <i data-lucide="search" style="width: 16px; height: 16px; color: var(--color-text-muted);"></i>
            <input type="text" placeholder="Cari NRM atau Pasien..." style="border: none; outline: none; font-family: var(--font-family);">
        </div>
        <button class="btn btn-primary">
            <i data-lucide="plus" style="width: 18px; height: 18px;"></i>
            Pasien Baru
        </button>
    </div>
</div>

<div class="dashboard-grid">
    <!-- Metrics -->
    <div class="card col-span-3">
        <h3 class="card-title">Total Pasien Hari Ini</h3>
        <div class="metric-value">142</div>
        <div class="metric-label" style="color: var(--color-success); display: flex; align-items: center; gap: 4px;">
            <i data-lucide="trending-up" style="width: 14px; height: 14px;"></i>
            +12% dari kemarin
        </div>
    </div>
    
    <div class="card col-span-3">
        <h3 class="card-title">Kapasitas UGD</h3>
        <div class="metric-value">18 <span style="font-size: 16px; color: var(--color-text-muted); font-weight: normal;">/ 24 Bed</span></div>
        <div class="metric-label" style="color: var(--color-warning);">Terisi 75%</div>
    </div>

    <div class="card col-span-3">
        <h3 class="card-title">Rawat Inap Tersedia</h3>
        <div class="metric-value">45</div>
        <div class="metric-label">Update 5 menit lalu</div>
    </div>

    <div class="card col-span-3">
        <h3 class="card-title">Dokter Bertugas</h3>
        <div class="metric-value">32</div>
        <div class="metric-label">Shift Pagi</div>
    </div>

    <!-- Data Table -->
    <div class="card col-span-8">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
            <h3 class="card-title" style="margin: 0;">Antrean Poliklinik Berjalan</h3>
            <button class="btn btn-secondary" style="padding: 4px 12px; font-size: 12px;">Lihat Semua</button>
        </div>
        
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>No. RM</th>
                        <th>Nama Pasien</th>
                        <th>Poli Tujuan</th>
                        <th>Dokter</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>RM-240101</td>
                        <td>Budi Santoso</td>
                        <td>Poli Jantung</td>
                        <td>Dr. Handoko, Sp.JP</td>
                        <td><span class="badge success">Diperiksa</span></td>
                    </tr>
                    <tr>
                        <td>RM-240105</td>
                        <td>Siti Aminah</td>
                        <td>Poli Penyakit Dalam</td>
                        <td>Dr. Rina, Sp.PD</td>
                        <td><span class="badge warning">Menunggu</span></td>
                    </tr>
                    <tr>
                        <td>RM-240112</td>
                        <td>Ahmad Dahlan</td>
                        <td>Poli Bedah Umum</td>
                        <td>Dr. Wahyu, Sp.B</td>
                        <td><span class="badge warning">Menunggu</span></td>
                    </tr>
                    <tr>
                        <td>RM-240098</td>
                        <td>Diana Putri</td>
                        <td>Poli Anak</td>
                        <td>Dr. Maya, Sp.A</td>
                        <td><span class="badge success">Diperiksa</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Vitals / Alerts Sidebar -->
    <div class="card col-span-4">
        <h3 class="card-title" style="margin-bottom: 24px;">Peringatan Sistem</h3>
        
        <div style="display: flex; flex-direction: column; gap: 16px;">
            <div style="padding: 12px; border-left: 3px solid var(--color-danger); background-color: rgba(186, 26, 26, 0.05); border-radius: 0 var(--border-radius-sm) var(--border-radius-sm) 0;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
                    <span style="font-size: var(--font-size-label); color: var(--color-danger); font-weight: 700; text-transform: uppercase;">Prioritas Tinggi</span>
                    <span style="font-size: 11px; color: var(--color-text-muted);">10:42</span>
                </div>
                <p style="font-size: var(--font-size-body); font-weight: 500;">Oksigen Sentral Lantai 3 menipis.</p>
            </div>

            <div style="padding: 12px; border-left: 3px solid var(--color-warning); background-color: rgba(245, 158, 11, 0.05); border-radius: 0 var(--border-radius-sm) var(--border-radius-sm) 0;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
                    <span style="font-size: var(--font-size-label); color: #92400E; font-weight: 700; text-transform: uppercase;">Perhatian</span>
                    <span style="font-size: 11px; color: var(--color-text-muted);">10:15</span>
                </div>
                <p style="font-size: var(--font-size-body); font-weight: 500;">Kapasitas UGD mendekati batas maksimal (75%).</p>
            </div>
        </div>
    </div>
</div>
@endsection
