@extends('layouts.app')

@section('title', 'Detail Jadwal')

@section('content')
    <div class="page-header">
        <div>
            <h1 class="page-title">Detail Jadwal Dokter</h1>
            <p class="metric-label">{{ $jadwal->dokter->nama_dokter }}</p>
        </div>
        <div style="display: flex; gap: 8px;">
            <a href="{{ route('jadwal.edit', $jadwal->id) }}" class="btn btn-warning">
                <i data-lucide="edit"></i>
                Edit
            </a>
            <a href="{{ route('jadwal.index') }}" class="btn btn-secondary">
                <i data-lucide="arrow-left"></i>
                Kembali
            </a>
        </div>
    </div>

    <div class="dashboard-grid">
        <!-- Jadwal Info -->
        <div class="card col-span-12">
            <h3 class="card-title">Jadwal Praktik</h3>

            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Nama Dokter</span>
                    <span class="info-value">{{ $jadwal->dokter->nama_dokter }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Spesialis</span>
                    <span class="info-value badge badge-info">{{ $jadwal->dokter->spesialis->nama_spesialis ?? '-' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Hari Praktik</span>
                    <span class="info-value">{{ $jadwal->hari }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Jam Praktik</span>
                    <span class="info-value">{{ $jadwal->getFormattedJamMulai() }} -
                        {{ $jadwal->getFormattedJamSelesai() }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Durasi</span>
                    <span class="info-value">{{ $jadwal->getDurasi() }} Menit</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Kuota Pasien</span>
                    <span class="info-value badge badge-primary">{{ $jadwal->kuota_pasien }} Pasien</span>
                </div>
            </div>
        </div>
    </div>

    <style>
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 24px;
            margin-top: 16px;
        }

        .info-item {
            display: flex;
            flex-direction: column;
            gap: 8px;
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

        .badge-primary {
            background-color: rgba(59, 130, 246, 0.1);
            color: #3b82f6;
        }
    </style>

    <script>
        lucide.createIcons();
    </script>
@endsection
