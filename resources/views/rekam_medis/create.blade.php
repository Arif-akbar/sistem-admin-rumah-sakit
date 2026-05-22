@extends('layouts.app')

@section('title', 'Tambah Rekam Medis')

@section('content')
    <div class="page-header">
        <div>
            <h1 class="page-title">Input Rekam Medis</h1>
            <p class="metric-label">Catat diagnosis dan penanganan pasien</p>
        </div>
        <a href="{{ route('rekam_medis.index') }}" class="btn btn-secondary">
            <i data-lucide="arrow-left"></i>
            Kembali
        </a>
    </div>

    <div class="card" style="max-width: 900px;">
        <form method="POST" action="{{ route('rekam_medis.store') }}" class="form">
            @csrf

            <!-- Pasien & Dokter -->
            <div class="form-section">
                <h3 class="form-section-title">Data Kunjungan</h3>

                <div class="form-row">
                    <div class="form-group">
                        <label for="no_rm">No. RM (Pasien) <span class="required">*</span></label>
                        <select id="no_rm" name="no_rm" class="form-control @error('no_rm') is-invalid @enderror">
                            <option value="">-- Pilih Pasien --</option>
                            @foreach ($pasiens as $pasien)
                                <option value="{{ $pasien->no_rm }}" {{ old('no_rm') == $pasien->no_rm ? 'selected' : '' }}>
                                    {{ $pasien->no_rm }} - {{ $pasien->nama_pasien }}
                                </option>
                            @endforeach
                        </select>
                        @error('no_rm')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="id_dokter">Dokter Pemeriksa <span class="required">*</span></label>
                        <select id="id_dokter" name="id_dokter"
                            class="form-control @error('id_dokter') is-invalid @enderror">
                            <option value="">-- Pilih Dokter --</option>
                            @foreach ($dokters as $dokter)
                                <option value="{{ $dokter->id_dokter }}"
                                    {{ old('id_dokter') == $dokter->id_dokter ? 'selected' : '' }}>
                                    {{ $dokter->nama_dokter }} ({{ $dokter->spesialis->nama_spesialis ?? '-' }})
                                </option>
                            @endforeach
                        </select>
                        @error('id_dokter')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="tgl_periksa">Tanggal Periksa <span class="required">*</span></label>
                        <input type="date" id="tgl_periksa" name="tgl_periksa"
                            value="{{ old('tgl_periksa', date('Y-m-d')) }}"
                            class="form-control @error('tgl_periksa') is-invalid @enderror">
                        @error('tgl_periksa')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="jam_periksa">Jam Periksa <span class="required">*</span></label>
                        <input type="time" id="jam_periksa" name="jam_periksa"
                            value="{{ old('jam_periksa', date('H:i')) }}"
                            class="form-control @error('jam_periksa') is-invalid @enderror">
                        @error('jam_periksa')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Keluhan & Diagnosis -->
            <div class="form-section">
                <h3 class="form-section-title">Diagnosis & Tindakan</h3>

                <div class="form-group">
                    <label for="keluhan_utama">Keluhan Utama <span class="required">*</span></label>
                    <textarea id="keluhan_utama" name="keluhan_utama" rows="3" placeholder="Keluhan pasien saat periksa"
                        class="form-control @error('keluhan_utama') is-invalid @enderror">{{ old('keluhan_utama') }}</textarea>
                    @error('keluhan_utama')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="diagnosis">Diagnosis</label>
                    <textarea id="diagnosis" name="diagnosis" rows="3" placeholder="Diagnosis dari dokter" class="form-control">{{ old('diagnosis') }}</textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="kode_icd10">Kode ICD-10</label>
                        <input type="text" id="kode_icd10" name="kode_icd10" placeholder="Contoh: A00.0"
                            value="{{ old('kode_icd10') }}" class="form-control">
                    </div>
                </div>

                <div class="form-group">
                    <label for="tindakan">Tindakan Medis</label>
                    <textarea id="tindakan" name="tindakan" rows="3" placeholder="Tindakan/prosedur yang dilakukan"
                        class="form-control">{{ old('tindakan') }}</textarea>
                </div>

                <div class="form-group">
                    <label for="resep">Resep Obat</label>
                    <textarea id="resep" name="resep" rows="4" placeholder="Daftar obat yang diberikan" class="form-control">{{ old('resep') }}</textarea>
                </div>
            </div>

            <!-- Vital Signs -->
            <div class="form-section">
                <h3 class="form-section-title">Vital Signs</h3>

                <div class="form-row">
                    <div class="form-group">
                        <label for="tekanan_darah">Tekanan Darah (Sistol/Diastol)</label>
                        <input type="text" id="tekanan_darah" name="tekanan_darah" placeholder="120/80"
                            value="{{ old('tekanan_darah') }}" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="denyut_nadi">Denyut Nadi (bpm)</label>
                        <input type="number" id="denyut_nadi" name="denyut_nadi" placeholder="72"
                            value="{{ old('denyut_nadi') }}" class="form-control">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="respirasi">Respirasi (napas/menit)</label>
                        <input type="number" id="respirasi" name="respirasi" placeholder="20"
                            value="{{ old('respirasi') }}" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="suhu_tubuh">Suhu Tubuh (°C)</label>
                        <input type="number" id="suhu_tubuh" name="suhu_tubuh" step="0.1" placeholder="36.5"
                            value="{{ old('suhu_tubuh') }}" class="form-control">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="berat_badan">Berat Badan (kg)</label>
                        <input type="number" id="berat_badan" name="berat_badan" step="0.1" placeholder="70"
                            value="{{ old('berat_badan') }}" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="tinggi_badan">Tinggi Badan (cm)</label>
                        <input type="number" id="tinggi_badan" name="tinggi_badan" placeholder="170"
                            value="{{ old('tinggi_badan') }}" class="form-control">
                    </div>
                </div>
            </div>

            <!-- Catatan -->
            <div class="form-section">
                <h3 class="form-section-title">Catatan Tambahan</h3>

                <div class="form-group">
                    <label for="catatan">Catatan</label>
                    <textarea id="catatan" name="catatan" rows="3" placeholder="Informasi tambahan atau follow-up"
                        class="form-control">{{ old('catatan') }}</textarea>
                </div>
            </div>

            <!-- Buttons -->
            <div class="form-section" style="border-top: 1px solid var(--color-border); padding-top: 24px;">
                <div class="form-actions">
                    <a href="{{ route('rekam_medis.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">
                        <i data-lucide="save"></i>
                        Simpan Rekam Medis
                    </button>
                </div>
            </div>
        </form>
    </div>

    <style>
        .form-section-title {
            font-size: 14px;
            font-weight: 600;
            color: var(--color-text);
            margin-bottom: 16px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin-bottom: 16px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 8px;
            color: var(--color-text);
        }

        .required {
            color: #dc3545;
        }

        .form-control {
            padding: 10px 12px;
            border: 1px solid var(--color-border);
            border-radius: 6px;
            font-family: var(--font-family);
            font-size: 13px;
            resize: vertical;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--color-primary);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .form-control.is-invalid {
            border-color: #dc3545;
        }

        .form-error {
            color: #dc3545;
            font-size: 12px;
            margin-top: 6px;
        }

        .form-actions {
            display: flex;
            gap: 12px;
            justify-content: flex-end;
        }
    </style>

    <script>
        lucide.createIcons();
    </script>
@endsection
