@extends('layouts.app')

@section('title', 'Tambah Pasien')

@section('content')
    <div class="page-header">
        <div>
            <h1 class="page-title">Tambah Pasien Baru</h1>
            <p class="metric-label">Daftarkan pasien baru ke sistem</p>
        </div>
        <a href="{{ route('pasien.index') }}" class="btn btn-secondary">
            <i data-lucide="arrow-left"></i>
            Kembali
        </a>
    </div>

    <div class="card" style="max-width: 800px;">
        <form method="POST" action="{{ route('pasien.store') }}" class="form">
            @csrf

            <!-- Data Pribadi -->
            <div class="form-section">
                <h3 class="form-section-title">Data Pribadi</h3>

                <div class="form-row">
                    <div class="form-group">
                        <label for="nik">NIK <span class="required">*</span></label>
                        <input type="text" id="nik" name="nik"
                            placeholder="Nomor Identitas Kependudukan (16 digit)" value="{{ old('nik') }}"
                            class="form-control @error('nik') is-invalid @enderror" maxlength="16" pattern="[0-9]{16}">
                        @error('nik')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="nama_pasien">Nama Lengkap <span class="required">*</span></label>
                        <input type="text" id="nama_pasien" name="nama_pasien" placeholder="Nama lengkap pasien"
                            value="{{ old('nama_pasien') }}"
                            class="form-control @error('nama_pasien') is-invalid @enderror">
                        @error('nama_pasien')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="tgl_lahir">Tanggal Lahir <span class="required">*</span></label>
                        <input type="date" id="tgl_lahir" name="tgl_lahir" value="{{ old('tgl_lahir') }}"
                            class="form-control @error('tgl_lahir') is-invalid @enderror">
                        @error('tgl_lahir')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="tempat_lahir">Tempat Lahir</label>
                        <input type="text" id="tempat_lahir" name="tempat_lahir" placeholder="Kota/Kabupaten"
                            value="{{ old('tempat_lahir') }}" class="form-control">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="jenkel">Jenis Kelamin <span class="required">*</span></label>
                        <select id="jenkel" name="jenkel" class="form-control @error('jenkel') is-invalid @enderror">
                            <option value="">-- Pilih --</option>
                            <option value="Laki-Laki" {{ old('jenkel') == 'Laki-Laki' ? 'selected' : '' }}>Laki-Laki
                            </option>
                            <option value="Perempuan" {{ old('jenkel') == 'Perempuan' ? 'selected' : '' }}>Perempuan
                            </option>
                        </select>
                        @error('jenkel')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="id_agama">Agama <span class="required">*</span></label>
                        <select id="id_agama" name="id_agama" class="form-control @error('id_agama') is-invalid @enderror">
                            <option value="">-- Pilih Agama --</option>
                            @foreach ($agamas as $agama)
                                <option value="{{ $agama->id }}" {{ old('id_agama') == $agama->id ? 'selected' : '' }}>
                                    {{ $agama->nama_agama ?? $agama->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_agama')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="golongan_darah">Golongan Darah</label>
                        <select id="golongan_darah" name="golongan_darah" class="form-control">
                            <option value="Tidak Diketahui">Tidak Diketahui</option>
                            <option value="A" {{ old('golongan_darah') == 'A' ? 'selected' : '' }}>A</option>
                            <option value="B" {{ old('golongan_darah') == 'B' ? 'selected' : '' }}>B</option>
                            <option value="AB" {{ old('golongan_darah') == 'AB' ? 'selected' : '' }}>AB</option>
                            <option value="O" {{ old('golongan_darah') == 'O' ? 'selected' : '' }}>O</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="telepon">Nomor Telepon</label>
                        <input type="tel" id="telepon" name="telepon" placeholder="0812345678"
                            value="{{ old('telepon') }}" class="form-control">
                    </div>
                </div>
            </div>

            <!-- Alamat -->
            <div class="form-section">
                <h3 class="form-section-title">Alamat Tinggal</h3>

                <div class="form-group">
                    <label for="alamat">Alamat Lengkap <span class="required">*</span></label>
                    <textarea id="alamat" name="alamat" rows="3" placeholder="Jalan, nomor rumah, dan detail lainnya"
                        class="form-control @error('alamat') is-invalid @enderror">{{ old('alamat') }}</textarea>
                    @error('alamat')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-row">
                    <div class="form-group" style="flex: 0.5;">
                        <label for="rt">RT</label>
                        <input type="text" id="rt" name="rt" placeholder="01"
                            value="{{ old('rt') }}" class="form-control">
                    </div>
                    <div class="form-group" style="flex: 0.5;">
                        <label for="rw">RW</label>
                        <input type="text" id="rw" name="rw" placeholder="01"
                            value="{{ old('rw') }}" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="kelurahan">Kelurahan</label>
                        <input type="text" id="kelurahan" name="kelurahan" value="{{ old('kelurahan') }}"
                            class="form-control">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="kecamatan">Kecamatan</label>
                        <input type="text" id="kecamatan" name="kecamatan" value="{{ old('kecamatan') }}"
                            class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="kota">Kota/Kabupaten</label>
                        <input type="text" id="kota" name="kota" value="{{ old('kota') }}"
                            class="form-control">
                    </div>
                    <div class="form-group" style="flex: 0.8;">
                        <label for="kode_pos">Kode Pos</label>
                        <input type="text" id="kode_pos" name="kode_pos" placeholder="12345"
                            value="{{ old('kode_pos') }}" class="form-control">
                    </div>
                </div>
            </div>

            <!-- Kesehatan -->
            <div class="form-section">
                <h3 class="form-section-title">Informasi Kesehatan</h3>

                <div class="form-group">
                    <label for="no_bpjs">Nomor BPJS</label>
                    <input type="text" id="no_bpjs" name="no_bpjs" placeholder="Nomor peserta BPJS (opsional)"
                        value="{{ old('no_bpjs') }}" class="form-control @error('no_bpjs') is-invalid @enderror">
                    @error('no_bpjs')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Buttons -->
            <div class="form-section" style="border-top: 1px solid var(--color-border); padding-top: 24px;">
                <div class="form-actions">
                    <a href="{{ route('pasien.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">
                        <i data-lucide="save"></i>
                        Simpan Pasien
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
            transition: all 0.2s;
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
