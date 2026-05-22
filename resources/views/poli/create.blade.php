@extends('layouts.app')

@section('title', 'Tambah Poli')

@section('content')
    <div class="page-header">
        <div>
            <h1 class="page-title">Tambah Poli Baru</h1>
            <p class="metric-label">Daftarkan unit layanan baru</p>
        </div>
        <a href="{{ route('poli.index') }}" class="btn btn-secondary">
            <i data-lucide="arrow-left"></i>
            Kembali
        </a>
    </div>

    <div class="card" style="max-width: 700px;">
        <form method="POST" action="{{ route('poli.store') }}" class="form">
            @csrf

            <div class="form-section">
                <h3 class="form-section-title">Informasi Poli</h3>

                <div class="form-group">
                    <label for="nama_poli">Nama Poli <span class="required">*</span></label>
                    <input type="text" id="nama_poli" name="nama_poli" placeholder="Nama unit layanan"
                        value="{{ old('nama_poli') }}" class="form-control @error('nama_poli') is-invalid @enderror">
                    @error('nama_poli')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="deskripsi">Deskripsi</label>
                    <textarea id="deskripsi" name="deskripsi" rows="4" placeholder="Deskripsi singkat tentang poli"
                        class="form-control">{{ old('deskripsi') }}</textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="lokasi">Lokasi Ruangan</label>
                        <input type="text" id="lokasi" name="lokasi" placeholder="Nama gedung/lantai"
                            value="{{ old('lokasi') }}" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="telepon">Telepon</label>
                        <input type="tel" id="telepon" name="telepon" placeholder="0812345678"
                            value="{{ old('telepon') }}" class="form-control">
                    </div>
                </div>
            </div>

            <div class="form-section">
                <h3 class="form-section-title">Jam Operasional</h3>

                <div class="form-row">
                    <div class="form-group">
                        <label for="jam_buka">Jam Buka</label>
                        <input type="time" id="jam_buka" name="jam_buka" value="{{ old('jam_buka') }}"
                            class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="jam_tutup">Jam Tutup</label>
                        <input type="time" id="jam_tutup" name="jam_tutup" value="{{ old('jam_tutup') }}"
                            class="form-control">
                        @error('jam_tutup')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="form-section">
                <h3 class="form-section-title">Status</h3>

                <div class="form-group">
                    <label for="status">Status <span class="required">*</span></label>
                    <select id="status" name="status" class="form-control @error('status') is-invalid @enderror">
                        <option value="Aktif" {{ old('status', 'Aktif') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="Non-Aktif" {{ old('status') == 'Non-Aktif' ? 'selected' : '' }}>Non-Aktif</option>
                    </select>
                    @error('status')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-section" style="border-top: 1px solid var(--color-border); padding-top: 24px;">
                <div class="form-actions">
                    <a href="{{ route('poli.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">
                        <i data-lucide="save"></i>
                        Simpan Poli
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
