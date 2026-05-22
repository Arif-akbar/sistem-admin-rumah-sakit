@extends('layouts.app')

@section('title', 'Data Dokter')

@section('content')
    <div class="page-header">
        <div>
            <h1 class="page-title">Data Dokter</h1>
            <p class="metric-label">Kelola informasi dokter dan spesialisasi</p>
        </div>
        <a href="{{ route('dokter.create') }}" class="btn btn-primary">
            <i data-lucide="plus"></i>
            Tambah Dokter
        </a>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible">
            <i data-lucide="check-circle"></i>
            <div>
                <strong>Sukses!</strong>
                <p>{{ $message }}</p>
            </div>
            <button type="button" class="btn-close" data-dismiss="alert"></button>
        </div>
    @endif

    <!-- Search & Filter -->
    <div class="card" style="margin-bottom: 24px;">
        <form method="GET" action="{{ route('dokter.index') }}" class="form-row">
            <div class="form-group" style="flex: 1;">
                <input type="text" name="search" placeholder="Cari nama dokter atau No.SIP..."
                    value="{{ request('search') }}" class="form-control">
            </div>
            <select name="id_spesialis" class="form-control" style="max-width: 200px;">
                <option value="">Semua Spesialis</option>
                @foreach ($spesialis as $s)
                    <option value="{{ $s->id }}" {{ request('id_spesialis') == $s->id ? 'selected' : '' }}>
                        {{ $s->nama_spesialis }}
                    </option>
                @endforeach
            </select>
            <select name="id_poli" class="form-control" style="max-width: 150px;">
                <option value="">Semua Poli</option>
                @foreach ($polis as $p)
                    <option value="{{ $p->id_poli }}" {{ request('id_poli') == $p->id_poli ? 'selected' : '' }}>
                        {{ $p->nama_poli }}
                    </option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-secondary">
                <i data-lucide="search"></i>
                Cari
            </button>
            <a href="{{ route('dokter.index') }}" class="btn btn-secondary">Reset</a>
        </form>
    </div>

    <!-- Table -->
    <div class="card">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nama Dokter</th>
                        <th>No. SIP</th>
                        <th>Spesialis</th>
                        <th>Poli</th>
                        <th>Telepon</th>
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
                                <span class="badge badge-info">
                                    {{ $dokter->spesialis->nama_spesialis ?? '-' }}
                                </span>
                            </td>
                            <td>{{ $dokter->poli->nama_poli ?? '-' }}</td>
                            <td>{{ $dokter->telepon ?? '-' }}</td>
                            <td>
                                <span class="badge {{ $dokter->status === 'Aktif' ? 'badge-success' : 'badge-warning' }}">
                                    {{ $dokter->status }}
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('dokter.show', $dokter->id_dokter) }}" class="btn btn-sm btn-info"
                                        title="Lihat Detail">
                                        <i data-lucide="eye"></i>
                                    </a>
                                    <a href="{{ route('dokter.edit', $dokter->id_dokter) }}" class="btn btn-sm btn-warning"
                                        title="Edit">
                                        <i data-lucide="edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-danger"
                                        onclick="confirmDelete('{{ route('dokter.destroy', $dokter->id_dokter) }}', '{{ $dokter->nama_dokter }}')"
                                        title="Hapus">
                                        <i data-lucide="trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">
                                <i data-lucide="inbox"></i>
                                <p>Tidak ada data dokter</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($dokters->hasPages())
            <div class="pagination-wrapper">
                {{ $dokters->links() }}
            </div>
        @endif
    </div>

    <!-- Delete Modal -->
    <div id="deleteModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Konfirmasi Hapus</h3>
                <button type="button" class="btn-close" onclick="closeDeleteModal()"></button>
            </div>
            <div class="modal-body">
                <p>Anda yakin ingin menghapus dokter <strong id="deleteDokterName"></strong>?</p>
                <p style="color: #dc3545; font-size: 13px; margin-top: 12px;">
                    <i data-lucide="alert-triangle" style="width: 14px; height: 14px; display: inline;"></i>
                    Semua jadwal dokter akan dihapus.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeDeleteModal()">Batal</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function confirmDelete(url, name) {
            document.getElementById('deleteDokterName').textContent = name;
            document.getElementById('deleteForm').action = url;
            document.getElementById('deleteModal').style.display = 'block';
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').style.display = 'none';
        }

        window.onclick = function(event) {
            const modal = document.getElementById('deleteModal');
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        }

        lucide.createIcons();
    </script>
@endsection
