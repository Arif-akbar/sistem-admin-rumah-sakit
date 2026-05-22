@extends('layouts.app')

@section('title', 'Jadwal Dokter')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Jadwal Dokter</h1>
        <p class="metric-label">Manajemen shift operasional dokter</p>
    </div>
    <div style="display: flex; gap: 12px;">
        <div class="input-field" style="display: flex; align-items: center; gap: 8px; padding: 8px 12px; background: white;">
            <i data-lucide="search" style="width: 16px; height: 16px; color: var(--color-text-muted);"></i>
            <input type="text" placeholder="Cari Dokter / Poli..." style="border: none; outline: none; font-family: var(--font-family); width: 200px;">
        </div>
        <button class="btn btn-primary">
            <i data-lucide="plus" style="width: 18px; height: 18px;"></i>
            Tambah Jadwal
        </button>
    </div>
</div>

<div class="dashboard-grid">
    <div class="card col-span-12">
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Hari</th>
                        <th>Dokter</th>
                        <th>Spesialisasi / Poli</th>
                        <th>Waktu Shift</th>
                        <th>Kuota Maks</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $hari_map = [
                            1 => 'Senin',
                            2 => 'Selasa',
                            3 => 'Rabu',
                            4 => 'Kamis',
                            5 => 'Jumat',
                            6 => 'Sabtu',
                            7 => 'Minggu'
                        ];
                    @endphp

                    @forelse ($jadwals as $jadwal)
                    <tr>
                        <td style="font-weight: 600;">{{ $hari_map[$jadwal->hari] ?? 'Unknown' }}</td>
                        <td>
                            <div style="font-weight: 600; color: var(--color-primary);">{{ $jadwal->dokter->nama_dokter ?? '-' }}</div>
                            <div style="font-size: 12px; color: var(--color-text-muted);">SIP: {{ $jadwal->dokter->sip_number ?? '-' }}</div>
                        </td>
                        <td>
                            <div>{{ $jadwal->dokter->spesialis->nama ?? '-' }}</div>
                            <div style="font-size: 12px; color: var(--color-text-muted);">Poli: {{ $jadwal->dokter->poli->nama_poli ?? '-' }}</div>
                        </td>
                        <td style="font-family: monospace; font-size: 14px;">
                            {{ substr($jadwal->jam_mulai, 0, 5) }} - {{ substr($jadwal->jam_selesai, 0, 5) }}
                        </td>
                        <td>{{ $jadwal->kuota }} Pasien</td>
                        <td>
                            @if($jadwal->is_active)
                                <span class="badge success">Aktif</span>
                            @else
                                <span class="badge danger">Cuti / Non-Aktif</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 32px; color: var(--color-text-muted);">
                            <i data-lucide="calendar" style="width: 48px; height: 48px; margin: 0 auto 12px; opacity: 0.5;"></i>
                            <p>Belum ada jadwal dokter terdaftar.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
