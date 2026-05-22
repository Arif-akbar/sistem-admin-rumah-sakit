@extends('layouts.app')

@section('title', 'Detail Poli')

@section('content')
    <div class="page-header">
        <div>
            <h1 class="page-title">Detail Poli</h1>
            <p class="metric-label">{{ $poli->nama_poli }}</p>
        </div>
        <div style="display: flex; gap: 8px;">
            <a href="{{ route('poli.edit', $poli->id_poli) }}" class="btn btn-warning">
                <i data-lucide="edit"></i>
                Edit
            </a>
            <a href="{{ route('poli.index') }}" class="btn btn-secondary">
                <i data-lucide="arrow-left"></i>
                Kembali
            </a>
        </div>
    </div>

    <div class="dashboard-grid">
        <!-- Info Card -->
        <div class="card col-span-6">
            <h3 class="card-title">Informasi Poli</h3>

            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Nama Poli</span>
                    <span class="info-value">{{ $poli->nama_poli }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Lokasi</span>
                    <span class="info-value">{{ $poli->lokasi ?? '-' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Telepon</span>
                    <span class="info-value">{{ $poli->telepon ?? '-' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Status</span>
                    <span class="info-value">
                        <span class="badge {{ $poli->status === 'Aktif' ? 'badge-success' : 'badge-warning' }}">
                            {{ $poli->status }}
                        </span>
                    </span>
                </div>
            </div>
        </div>

        <!-- Operational Info -->
        <div class="card col-span-6">
            <h3 class="card-title">Jam Operasional</h3>

            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Jam Buka - Tutup</span>
                    <span class="info-value">
                        {{ $poli->jam_buka ? \Carbon\Carbon::createFromFormat('H:i:s', $poli->jam_buka)->format('H:i') : '-' }}
                        -
                        {{ $poli->jam_tutup ? \Carbon\Carbon::createFromFormat('H:i:s', $poli->jam_tutup)->format('H:i') : '-' }}
                    </span>
                </div>
                <div class="info-item">
                    <span class="info-label">Jumlah Dokter</span>
                    <span class="info-value">
                        <span class="badge badge-primary">{{ $poli->getJumlahDokter() }} Dokter</span>
                    </span>
                </div>
            </div>
        </div>

        <!-- Description -->
        @if ($poli->deskripsi)
            <div class="card col-span-12">
                <h3 class="card-title">Deskripsi</h3>
                <p>{{ $poli->deskripsi }}</p>
            </div>
        @endif

        <!-- Dokter Poli -->
        <div class="card col-span-12">
            <h3 class="card-title">Dokter yang Bertugas</h3>

            @if ($dokters && count($dokters) > 0)
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nama Dokter</th>
                                <th>No. SIP</th>
                                <th>Spesialis</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($dokters as $dokter)
                                <tr>
                                    <td>
                                        <strong>{{ $dokter->nama_dokter }}</strong>
                                    </td>
                                    <td>{{ $dokter->nomor_sip }}</td>
                                    <td>
                                        <span
                                            class="badge badge-info">{{ $dokter->spesialis->nama_spesialis ?? '-' }}</span>
                                    </td>
                                    <td>
                                        <span
                                            class="badge {{ $dokter->status === 'Aktif' ? 'badge-success' : 'badge-warning' }}">
                                            {{ $dokter->status }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('dokter.show', $dokter->id_dokter) }}"
                                            class="btn btn-sm btn-info">
                                            <i data-lucide="eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">Belum ada dokter yang terdaftar</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state">
                    <i data-lucide="users"></i>
                    <p>Belum ada dokter yang terdaftar di poli ini</p>
                </div>
            @endif
        </div>
    </div>

    <style>
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 16px;
        }

        .info-item {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .info-label {
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--color-text-muted);
        }

        .info-value {
            font-size: 14px;
            font-weight: 500;
            color: var(--color-text);
        }

        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
            width: fit-content;
        }

        .badge-primary {
            background-color: rgba(59, 130, 246, 0.1);
            color: #3b82f6;
        }

        .badge-info {
            background-color: rgba(59, 130, 246, 0.1);
            color: #3b82f6;
        }

        .badge-success {
            background-color: rgba(16, 185, 129, 0.1);
            color: #10b981;
        }

        .badge-warning {
            background-color: rgba(245, 158, 11, 0.1);
            color: #f59e0b;
        }

        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: var(--color-text-muted);
        }

        .empty-state i {
            width: 48px;
            height: 48px;
            margin-bottom: 12px;
        }
    </style>

    <script>
        lucide.createIcons();
    </script>
@endsection
