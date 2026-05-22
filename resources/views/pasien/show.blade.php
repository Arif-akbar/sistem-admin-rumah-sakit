@extends('layouts.app')

@section('title', 'Detail Pasien')

@section('content')
    <div class="page-header">
        <div>
            <h1 class="page-title">Detail Pasien</h1>
            <p class="metric-label">{{ $pasien->no_rm }} - {{ $pasien->nama_pasien }}</p>
        </div>
        <div style="display: flex; gap: 8px;">
            <a href="{{ route('pasien.edit', $pasien->no_rm) }}" class="btn btn-warning">
                <i data-lucide="edit"></i>
                Edit
            </a>
            <a href="{{ route('pasien.index') }}" class="btn btn-secondary">
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
                    <span class="info-label">No. RM</span>
                    <span class="info-value badge badge-primary">{{ $pasien->no_rm }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">NIK</span>
                    <span class="info-value">{{ $pasien->nik }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Nama Lengkap</span>
                    <span class="info-value">{{ $pasien->nama_pasien }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Jenis Kelamin</span>
                    <span class="info-value">
                        <span class="badge {{ $pasien->jenkel === 'Laki-Laki' ? 'badge-info' : 'badge-warning' }}">
                            {{ $pasien->jenkel }}
                        </span>
                    </span>
                </div>
                <div class="info-item">
                    <span class="info-label">Tanggal Lahir</span>
                    <span class="info-value">{{ $pasien->tgl_lahir->format('d M Y') }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Umur</span>
                    <span class="info-value">{{ $pasien->getUmur() }} tahun</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Tempat Lahir</span>
                    <span class="info-value">{{ $pasien->tempat_lahir ?? '-' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Agama</span>
                    <span class="info-value">{{ $pasien->agama->nama_agama ?? ($pasien->agama->name ?? '-') }}</span>
                </div>
            </div>
        </div>

        <!-- Health Info -->
        <div class="card col-span-6">
            <h3 class="card-title">Informasi Kesehatan</h3>

            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Golongan Darah</span>
                    <span class="info-value">
                        <span class="badge badge-danger">{{ $pasien->golongan_darah }}</span>
                    </span>
                </div>
                <div class="info-item">
                    <span class="info-label">No. BPJS</span>
                    <span class="info-value">{{ $pasien->no_bpjs ?? '-' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Telepon</span>
                    <span class="info-value">{{ $pasien->telepon ?? '-' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Status</span>
                    <span class="info-value">
                        <span class="badge badge-success">Aktif</span>
                    </span>
                </div>
                <div class="info-item">
                    <span class="info-label">Terdaftar Sejak</span>
                    <span class="info-value">{{ $pasien->created_at->format('d M Y H:i') }}</span>
                </div>
            </div>
        </div>

        <!-- Address -->
        <div class="card col-span-12">
            <h3 class="card-title">Alamat Tinggal</h3>

            <div class="info-grid">
                <div class="info-item col-span-12">
                    <span class="info-label">Alamat</span>
                    <span class="info-value">{{ $pasien->alamat }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">RT/RW</span>
                    <span class="info-value">{{ $pasien->rt ?? '-' }}/{{ $pasien->rw ?? '-' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Kelurahan</span>
                    <span class="info-value">{{ $pasien->kelurahan ?? '-' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Kecamatan</span>
                    <span class="info-value">{{ $pasien->kecamatan ?? '-' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Kota/Kabupaten</span>
                    <span class="info-value">{{ $pasien->kota ?? '-' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Kode Pos</span>
                    <span class="info-value">{{ $pasien->kode_pos ?? '-' }}</span>
                </div>
            </div>
        </div>

        <!-- Medical History -->
        <div class="card col-span-12">
            <h3 class="card-title">Riwayat Kunjungan</h3>

            @if ($rekamMedis && count($rekamMedis) > 0)
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Tanggal Periksa</th>
                                <th>Dokter</th>
                                <th>Keluhan Utama</th>
                                <th>Diagnosis</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($rekamMedis as $rekam)
                                <tr>
                                    <td>{{ $rekam->tgl_periksa->format('d M Y H:i') }}</td>
                                    <td>{{ $rekam->dokter->nama_dokter ?? '-' }}</td>
                                    <td>{{ Str::limit($rekam->keluhan_utama, 50) }}</td>
                                    <td>{{ $rekam->diagnosis ?? '-' }}</td>
                                    <td>
                                        <a href="{{ route('rekam_medis.show', $rekam->id) }}" class="btn btn-sm btn-info">
                                            <i data-lucide="eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">Tidak ada riwayat kunjungan</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state">
                    <i data-lucide="inbox"></i>
                    <p>Belum ada riwayat kunjungan</p>
                </div>
            @endif
        </div>
    </div>

    <style>
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 24px;
            margin-top: 16px;
        }

        .info-item {
            display: flex;
            flex-direction: column;
            gap: 6px;
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

        .badge-danger {
            background-color: rgba(220, 53, 69, 0.1);
            color: #dc3545;
        }

        .badge-success {
            background-color: rgba(16, 185, 129, 0.1);
            color: #10b981;
        }

        .badge-info {
            background-color: rgba(17, 24, 39, 0.1);
            color: #111827;
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
