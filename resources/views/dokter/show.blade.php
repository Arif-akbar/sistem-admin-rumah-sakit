@extends('layouts.app')

@section('title', 'Detail Dokter')

@section('content')
    <div class="page-header">
        <div>
            <h1 class="page-title">Detail Dokter</h1>
            <p class="metric-label">{{ $dokter->nama_dokter }}</p>
        </div>
        <div style="display: flex; gap: 8px;">
            <a href="{{ route('dokter.edit', $dokter->id_dokter) }}" class="btn btn-warning">
                <i data-lucide="edit"></i>
                Edit
            </a>
            <a href="{{ route('dokter.index') }}" class="btn btn-secondary">
                <i data-lucide="arrow-left"></i>
                Kembali
            </a>
        </div>
    </div>

    <div class="dashboard-grid">
        <!-- Info Card -->
        <div class="card col-span-6">
            <h3 class="card-title">Informasi Pribadi</h3>

            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Nama Dokter</span>
                    <span class="info-value">{{ $dokter->nama_dokter }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">No. SIP</span>
                    <span class="info-value">{{ $dokter->nomor_sip }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Spesialis</span>
                    <span class="info-value badge badge-info">
                        {{ $dokter->spesialis->nama_spesialis ?? '-' }}
                    </span>
                </div>
                <div class="info-item">
                    <span class="info-label">Poli</span>
                    <span class="info-value">{{ $dokter->poli->nama_poli ?? '-' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Status</span>
                    <span class="info-value">
                        <span class="badge {{ $dokter->status === 'Aktif' ? 'badge-success' : 'badge-warning' }}">
                            {{ $dokter->status }}
                        </span>
                    </span>
                </div>
            </div>
        </div>

        <!-- Contact Info -->
        <div class="card col-span-6">
            <h3 class="card-title">Informasi Kontak</h3>

            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Telepon</span>
                    <span class="info-value">{{ $dokter->telepon ?? '-' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Email</span>
                    <span class="info-value">{{ $dokter->email ?? '-' }}</span>
                </div>
            </div>
        </div>

        <!-- Address -->
        @if ($dokter->alamat)
            <div class="card col-span-12">
                <h3 class="card-title">Alamat</h3>
                <p>{{ $dokter->alamat }}</p>
            </div>
        @endif

        <!-- Jadwal Praktik -->
        <div class="card col-span-12">
            <h3 class="card-title">Jadwal Praktik</h3>

            @if ($dokter->jadwal && count($dokter->jadwal) > 0)
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Hari</th>
                                <th>Jam Praktik</th>
                                <th>Kuota Pasien</th>
                                <th>Durasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($dokter->jadwal as $jadwal)
                                <tr>
                                    <td>
                                        <strong>{{ $jadwal->hari }}</strong>
                                    </td>
                                    <td>{{ $jadwal->getFormattedJamMulai() }} - {{ $jadwal->getFormattedJamSelesai() }}
                                    </td>
                                    <td>
                                        <span class="badge badge-primary">{{ $jadwal->kuota_pasien }} Pasien</span>
                                    </td>
                                    <td>{{ $jadwal->getDurasi() }} menit</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">Belum ada jadwal praktik</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state">
                    <i data-lucide="calendar"></i>
                    <p>Belum ada jadwal praktik</p>
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

        .badge-primary {
            background-color: rgba(59, 130, 246, 0.1);
            color: #3b82f6;
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
