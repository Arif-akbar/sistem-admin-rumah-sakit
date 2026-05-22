@extends('layouts.app')

@section('title', 'Data Poli')

@section('content')
    <div class="page-header">
        <div>
            <h1 class="page-title">Data Poli</h1>
            <p class="metric-label">Kelola unit layanan/departemen poli</p>
        </div>
        <a href="{{ route('poli.create') }}" class="btn btn-primary">
            <i data-lucide="plus"></i>
            Tambah Poli
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
        <form method="GET" action="{{ route('poli.index') }}" class="form-row">
            <div class="form-group" style="flex: 1;">
                <input type="text" name="search" placeholder="Cari nama poli..." value="{{ request('search') }}"
                    class="form-control">
            </div>
            <select name="status" class="form-control" style="max-width: 150px;">
                <option value="">Semua Status</option>
                <option value="Aktif" {{ request('status') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="Non-Aktif" {{ request('status') == 'Non-Aktif' ? 'selected' : '' }}>Non-Aktif</option>
            </select>
            <button type="submit" class="btn btn-secondary">
                <i data-lucide="search"></i>
                Cari
            </button>
            <a href="{{ route('poli.index') }}" class="btn btn-secondary">Reset</a>
        </form>
    </div>

    <!-- Table -->
    <div class="card">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nama Poli</th>
                        <th>Lokasi</th>
                        <th>Jumlah Dokter</th>
                        <th>Jam Operasional</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($polis as $poli)
                        <tr>
                            <td>
                                <strong>{{ $poli->nama_poli }}</strong>
                            </td>
                            <td>{{ $poli->lokasi ?? '-' }}</td>
                            <td>
                                <span class="badge badge-primary">{{ $poli->getJumlahDokter() }} Dokter</span>
                            </td>
                            <td>
                                {{ $poli->jam_buka ? \Carbon\Carbon::createFromFormat('H:i:s', $poli->jam_buka)->format('H:i') : '-' }}
                                -
                                {{ $poli->jam_tutup ? \Carbon\Carbon::createFromFormat('H:i:s', $poli->jam_tutup)->format('H:i') : '-' }}
                            </td>
                            <td>
                                <span class="badge {{ $poli->status === 'Aktif' ? 'badge-success' : 'badge-warning' }}">
                                    {{ $poli->status }}
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('poli.show', $poli->id_poli) }}" class="btn btn-sm btn-info"
                                        title="Lihat Detail">
                                        <i data-lucide="eye"></i>
                                    </a>
                                    <a href="{{ route('poli.edit', $poli->id_poli) }}" class="btn btn-sm btn-warning"
                                        title="Edit">
                                        <i data-lucide="edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-danger"
                                        onclick="confirmDelete('{{ route('poli.destroy', $poli->id_poli) }}', '{{ $poli->nama_poli }}')"
                                        title="Hapus">
                                        <i data-lucide="trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">
                                <i data-lucide="inbox"></i>
                                <p>Tidak ada data poli</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($polis->hasPages())
            <div class="pagination-wrapper">
                {{ $polis->links() }}
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
                <p>Anda yakin ingin menghapus poli <strong id="deletePoliName"></strong>?</p>
                <p style="color: #dc3545; font-size: 13px; margin-top: 12px;">
                    <i data-lucide="alert-triangle" style="width: 14px; height: 14px; display: inline;"></i>
                    Pastikan poli tidak memiliki dokter yang terdaftar.
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
            document.getElementById('deletePoliName').textContent = name;
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
