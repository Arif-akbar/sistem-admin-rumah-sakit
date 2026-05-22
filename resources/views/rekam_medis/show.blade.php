@extends('layouts.app')

@section('title', 'Detail Rekam Medis')

@section('content')
    <div class="page-header">
        <div>
            <h1 class="page-title">Detail Rekam Medis</h1>
            <p class="metric-label">{{ $rekamMedis->no_rm }} - {{ $rekamMedis->pasien->nama_pasien }}</p>
        </div>
        <div style="display: flex; gap: 8px;">
            <a href="{{ route('rekam_medis.cetak', $rekamMedis->id) }}" class="btn btn-info" target="_blank">
                <i data-lucide="printer"></i>
                Cetak
            </a>
            <a href="{{ route('rekam_medis.edit', $rekamMedis->id) }}" class="btn btn-warning">
                <i data-lucide="edit"></i>
                Edit
            </a>
            <a href="{{ route('rekam_medis.index') }}" class="btn btn-secondary">
                <i data-lucide="arrow-left"></i>
                Kembali
            </a>
        </div>
    </div>

    <div class="dashboard-grid">
        <!-- Pasien & Dokter Info -->
        <div class="card col-span-6">
            <h3 class="card-title">Informasi Pasien</h3>

            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">No. RM</span>
                    <span class="info-value badge badge-primary">{{ $rekamMedis->no_rm }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Nama Pasien</span>
                    <span class="info-value">{{ $rekamMedis->pasien->nama_pasien }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Umur</span>
                    <span class="info-value">{{ $rekamMedis->pasien->getUmur() }} tahun</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Gender</span>
                    <span class="info-value">{{ $rekamMedis->pasien->jenkel }}</span>
                </div>
            </div>
        </div>

        <!-- Dokter Info -->
        <div class="card col-span-6">
            <h3 class="card-title">Dokter Pemeriksa</h3>

            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Dokter</span>
                    <span class="info-value">{{ $rekamMedis->dokter->nama_dokter }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Spesialis</span>
                    <span
                        class="info-value badge badge-info">{{ $rekamMedis->dokter->spesialis->nama_spesialis ?? '-' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Tanggal Periksa</span>
                    <span class="info-value">{{ $rekamMedis->tgl_periksa->format('d M Y H:i') }}</span>
                </div>
            </div>
        </div>

        <!-- Keluhan & Diagnosis -->
        <div class="card col-span-12">
            <h3 class="card-title">Keluhan & Diagnosis</h3>

            <div class="info-grid">
                <div class="info-item col-span-12">
                    <span class="info-label">Keluhan Utama</span>
                    <span class="info-value">{{ $rekamMedis->keluhan_utama }}</span>
                </div>
                <div class="info-item col-span-12">
                    <span class="info-label">Diagnosis</span>
                    <span class="info-value">{{ $rekamMedis->diagnosis ?? '-' }}</span>
                </div>
                @if ($rekamMedis->kode_icd10)
                    <div class="info-item">
                        <span class="info-label">Kode ICD-10</span>
                        <span class="info-value badge badge-warning">{{ $rekamMedis->kode_icd10 }}</span>
                    </div>
                @endif
            </div>
        </div>

        <!-- Vital Signs -->
        <div class="card col-span-12">
            <h3 class="card-title">Vital Signs</h3>

            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Tekanan Darah</span>
                    <span class="info-value">{{ $rekamMedis->tekanan_darah ?? '-' }} mmHg</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Denyut Nadi</span>
                    <span class="info-value">{{ $rekamMedis->denyut_nadi ?? '-' }} bpm</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Respirasi</span>
                    <span class="info-value">{{ $rekamMedis->respirasi ?? '-' }} x/menit</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Suhu Tubuh</span>
                    <span class="info-value">{{ $rekamMedis->suhu_tubuh ?? '-' }} °C</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Berat Badan</span>
                    <span class="info-value">{{ $rekamMedis->berat_badan ?? '-' }} kg</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Tinggi Badan</span>
                    <span class="info-value">{{ $rekamMedis->tinggi_badan ?? '-' }} cm</span>
                </div>
                @if ($rekamMedis->getIMB())
                    <div class="info-item">
                        <span class="info-label">IMB</span>
                        <span class="info-value">
                            <span class="badge badge-info">{{ number_format($rekamMedis->getIMB(), 2) }}</span>
                            ({{ $rekamMedis->getKategoriIMB() }})
                        </span>
                    </div>
                @endif
            </div>
        </div>

        <!-- Tindakan & Resep -->
        @if ($rekamMedis->tindakan)
            <div class="card col-span-6">
                <h3 class="card-title">Tindakan Medis</h3>
                <p>{{ $rekamMedis->tindakan }}</p>
            </div>
        @endif

        @if ($rekamMedis->resep)
            <div class="card col-span-6">
                <h3 class="card-title">Resep Obat</h3>
                <p style="white-space: pre-wrap;">{{ $rekamMedis->resep }}</p>
            </div>
        @endif

        <!-- Catatan -->
        @if ($rekamMedis->catatan)
            <div class="card col-span-12">
                <h3 class="card-title">Catatan Tambahan</h3>
                <p>{{ $rekamMedis->catatan }}</p>
            </div>
        @endif
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
            gap: 8px;
        }

        .info-item.col-span-12 {
            grid-column: 1 / -1;
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
            line-height: 1.5;
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

        .badge-warning {
            background-color: rgba(245, 158, 11, 0.1);
            color: #f59e0b;
        }

        .card p {
            white-space: pre-wrap;
            word-wrap: break-word;
        }
    </style>

    <script>
        lucide.createIcons();
    </script>
@endsection
