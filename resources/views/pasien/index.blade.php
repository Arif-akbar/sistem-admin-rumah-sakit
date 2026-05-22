@extends('layouts.app')

@section('title', 'Data Pasien')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Data Pasien</h1>
        <p class="metric-label">Manajemen data profil dan rekam pasien</p>
    </div>
    <div style="display: flex; gap: 12px;">
        <div class="input-field" style="display: flex; align-items: center; gap: 8px; padding: 8px 12px; background: white;">
            <i data-lucide="search" style="width: 16px; height: 16px; color: var(--color-text-muted);"></i>
            <input type="text" placeholder="Cari NRM, NIK, atau Nama..." style="border: none; outline: none; font-family: var(--font-family); width: 200px;">
        </div>
        <button class="btn btn-secondary">
            <i data-lucide="filter" style="width: 18px; height: 18px;"></i>
            Filter
        </button>
        <a href="{{ route('pasien.create') }}" class="btn btn-primary" style="text-decoration: none;">
            <i data-lucide="user-plus" style="width: 18px; height: 18px;"></i>
            Tambah Pasien
        </a>
    </div>
</div>

<div class="dashboard-grid">
    @if(session('success'))
    <div class="card col-span-12" style="background: rgba(16, 185, 129, 0.1); border-left: 4px solid var(--color-success); padding: 16px;">
        <div style="display: flex; align-items: center; gap: 8px; color: var(--color-success); font-weight: 600;">
            <i data-lucide="check-circle" style="width: 20px; height: 20px;"></i>
            {{ session('success') }}
        </div>
    </div>
    @endif

    <div class="card col-span-12">
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>No. RM</th>
                        <th>Nama Pasien</th>
                        <th>Jenis Kelamin</th>
                        <th>Umur / Tgl Lahir</th>
                        <th>No. Telepon</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pasiens as $pasien)
                    <tr>
                        <td style="font-weight: 500;">{{ $pasien->no_rm }}</td>
                        <td>
                            <div style="font-weight: 600;">{{ $pasien->nama_pasien }}</div>
                            <div style="font-size: 12px; color: var(--color-text-muted);">NIK: {{ $pasien->nik }}</div>
                        </td>
                        <td>
                            @if($pasien->jenkel == 'Laki-Laki')
                                <span class="badge" style="background: rgba(59, 130, 246, 0.1); color: #1D4ED8;">Laki-Laki</span>
                            @else
                                <span class="badge" style="background: rgba(236, 72, 153, 0.1); color: #BE185D;">Perempuan</span>
                            @endif
                        </td>
                        <td>
                            {{ \Carbon\Carbon::parse($pasien->tgl_lahir)->age }} Tahun
                            <div style="font-size: 12px; color: var(--color-text-muted);">{{ \Carbon\Carbon::parse($pasien->tgl_lahir)->format('d M Y') }}</div>
                        </td>
                        <td>{{ $pasien->telepon ?? '-' }}</td>
                        <td>
                            <div style="display: flex; gap: 8px;">
                                <button class="btn btn-secondary" style="padding: 4px 8px;" title="Lihat Detail">
                                    <i data-lucide="eye" style="width: 16px; height: 16px;"></i>
                                </button>
                                <button class="btn btn-secondary" style="padding: 4px 8px;" title="Edit Data">
                                    <i data-lucide="edit-3" style="width: 16px; height: 16px;"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 32px; color: var(--color-text-muted);">
                            <i data-lucide="users" style="width: 48px; height: 48px; margin: 0 auto 12px; opacity: 0.5;"></i>
                            <p>Belum ada data pasien.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($pasiens->hasPages())
        <div style="margin-top: 24px; padding-top: 16px; border-top: 1px solid var(--color-border);">
            {{ $pasiens->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
