@extends('layouts.app')

@section('title', 'Rekam Medis')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Data Rekam Medis</h1>
        <p class="metric-label">Riwayat diagnosis dan penanganan pasien</p>
    </div>
    <div style="display: flex; gap: 12px;">
        <div class="input-field" style="display: flex; align-items: center; gap: 8px; padding: 8px 12px; background: white;">
            <i data-lucide="search" style="width: 16px; height: 16px; color: var(--color-text-muted);"></i>
            <input type="text" placeholder="Cari NRM atau Diagnosis..." style="border: none; outline: none; font-family: var(--font-family); width: 220px;">
        </div>
        <a href="{{ route('rekam_medis.create') }}" class="btn btn-primary" style="text-decoration: none;">
            <i data-lucide="folder-plus" style="width: 18px; height: 18px;"></i>
            Input Data Baru
        </a>
    </div>
</div>

<div class="dashboard-grid">
    <div class="card col-span-12">
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Tgl Periksa</th>
                        <th>No. RM / Pasien</th>
                        <th>Dokter Pemeriksa</th>
                        <th>Diagnosis Utama</th>
                        <th>Tindakan / Terapi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($rekams as $rekam)
                    <tr>
                        <td style="font-weight: 500;">
                            {{ \Carbon\Carbon::parse($rekam->tgl_periksa)->format('d M Y') }}
                            <div style="font-size: 12px; color: var(--color-text-muted);">{{ \Carbon\Carbon::parse($rekam->tgl_periksa)->format('H:i') }}</div>
                        </td>
                        <td>
                            <div style="font-weight: 600;">{{ $rekam->no_rm }}</div>
                            <div style="font-size: 12px; color: var(--color-primary);">{{ $rekam->pasien->nama_pasien ?? '-' }}</div>
                        </td>
                        <td>
                            <div>{{ $rekam->dokter->nama_dokter ?? '-' }}</div>
                            <div style="font-size: 12px; color: var(--color-text-muted);">{{ $rekam->dokter->spesialis->nama_spesialis ?? '-' }}</div>
                        </td>
                        <td>
                            @if($rekam->kode_icd10)
                                <span class="badge" style="background: rgba(15, 23, 42, 0.1); color: var(--color-sidebar-bg); margin-bottom: 4px;">ICD: {{ $rekam->kode_icd10 }}</span>
                            @endif
                            <div style="font-weight: 500;">{{ $rekam->diagnosis ?? 'Menunggu Diagnosis' }}</div>
                        </td>
                        <td style="max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                            {{ $rekam->tindakan ?? $rekam->resep ?? '-' }}
                        </td>
                        <td>
                            <div style="display: flex; gap: 8px;">
                                <a href="{{ route('rekam_medis.show', $rekam->id) }}" class="btn btn-secondary"
                                    style="padding: 4px 8px;" title="Lihat Rekam Medis">
                                    <i data-lucide="file-text" style="width: 16px; height: 16px;"></i>
                                </a>
                                <a href="{{ route('rekam_medis.edit', $rekam->id) }}" class="btn btn-secondary"
                                    style="padding: 4px 8px;" title="Edit Rekam Medis">
                                    <i data-lucide="edit-3" style="width: 16px; height: 16px;"></i>
                                </a>
                                <form method="POST" action="{{ route('rekam_medis.destroy', $rekam->id) }}"
                                    onsubmit="return confirm('Hapus rekam medis ini?')" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-secondary" style="padding: 4px 8px;" title="Hapus">
                                        <i data-lucide="trash-2" style="width: 16px; height: 16px;"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 32px; color: var(--color-text-muted);">
                            <i data-lucide="folder-search" style="width: 48px; height: 48px; margin: 0 auto 12px; opacity: 0.5;"></i>
                            <p>Belum ada data rekam medis.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($rekams->hasPages())
        <div style="margin-top: 24px; padding-top: 16px; border-top: 1px solid var(--color-border);">
            {{ $rekams->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
