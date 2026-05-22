@extends('layouts.app')

@section('title', 'Tambah Jadwal Dokter')

@section('content')
    <div class="page-header">
        <div>
            <h1 class="page-title">Tambah Jadwal Dokter</h1>
            <p class="metric-label">Atur jadwal praktik dokter</p>
        </div>
        <a href="{{ route('jadwal.index') }}" class="btn btn-secondary">
            <i data-lucide="arrow-left"></i>
            Kembali
        </a>
    </div>

    <div class="card" style="max-width: 700px;">
        <form method="POST" action="{{ route('jadwal.store') }}" class="form">
            @csrf

            <div class="form-section">
                <h3 class="form-section-title">Jadwal Praktik</h3>

                <div class="form-group">
                    <label for="id_dokter">Dokter <span class="required">*</span></label>
                    <select id="id_dokter" name="id_dokter" class="form-control @error('id_dokter') is-invalid @enderror">
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

                <div class="form-row">
                    <div class="form-group">
                        <label for="hari">Hari <span class="required">*</span></label>
                        <select id="hari" name="hari" class="form-control @error('hari') is-invalid @enderror">
                            <option value="">-- Pilih Hari --</option>
                            @foreach ($days as $day)
                                <option value="{{ $day }}" {{ old('hari') == $day ? 'selected' : '' }}>
                                    {{ $day }}</option>
                            @endforeach
                        </select>
                        @error('hari')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="jam_mulai">Jam Mulai <span class="required">*</span></label>
                        <input type="time" id="jam_mulai" name="jam_mulai" value="{{ old('jam_mulai') }}"
                            class="form-control @error('jam_mulai') is-invalid @enderror">
                        @error('jam_mulai')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="jam_selesai">Jam Selesai <span class="required">*</span></label>
                        <input type="time" id="jam_selesai" name="jam_selesai" value="{{ old('jam_selesai') }}"
                            class="form-control @error('jam_selesai') is-invalid @enderror">
                        @error('jam_selesai')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="kuota_pasien">Kuota Pasien Per Sesi <span class="required">*</span></label>
                    <input type="number" id="kuota_pasien" name="kuota_pasien" min="1" max="100"
                        value="{{ old('kuota_pasien', 10) }}"
                        class="form-control @error('kuota_pasien') is-invalid @enderror">
                    @error('kuota_pasien')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-section" style="border-top: 1px solid var(--color-border); padding-top: 24px;">
                <div class="form-actions">
                    <a href="{{ route('jadwal.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">
                        <i data-lucide="save"></i>
                        Simpan Jadwal
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
