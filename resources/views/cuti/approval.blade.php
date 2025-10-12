@extends('layouts.app')

@section('title', 'Approval Cuti')

@section('content')
    <div class="container-fluid">

        <!-- Judul Halaman -->
        <h4 class="mb-4 text-gray-800 fw-bold">Approval Cuti</h4>

        <!-- Filter -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <form action="{{ route('cuti.approval') }}" method="GET" class="row g-3 align-items-end">

                    {{-- Filter Status Cuti --}}
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Status Cuti</label>
                        <select name="status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                            <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                            <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                    </div>

                    {{-- Filter Bulan --}}
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Bulan</label>
                        <select name="bulan" class="form-select">
                            <option value="1" {{ request('bulan', date('n')) == 1 ? 'selected' : '' }}>Januari</option>
                            <option value="2" {{ request('bulan', date('n')) == 2 ? 'selected' : '' }}>Februari</option>
                            <option value="3" {{ request('bulan', date('n')) == 3 ? 'selected' : '' }}>Maret</option>
                            <option value="4" {{ request('bulan', date('n')) == 4 ? 'selected' : '' }}>April</option>
                            <option value="5" {{ request('bulan', date('n')) == 5 ? 'selected' : '' }}>Mei</option>
                            <option value="6" {{ request('bulan', date('n')) == 6 ? 'selected' : '' }}>Juni</option>
                            <option value="7" {{ request('bulan', date('n')) == 7 ? 'selected' : '' }}>Juli</option>
                            <option value="8" {{ request('bulan', date('n')) == 8 ? 'selected' : '' }}>Agustus</option>
                            <option value="9" {{ request('bulan', date('n')) == 9 ? 'selected' : '' }}>September</option>
                            <option value="10" {{ request('bulan', date('n')) == 10 ? 'selected' : '' }}>Oktober</option>
                            <option value="11" {{ request('bulan', date('n')) == 11 ? 'selected' : '' }}>November</option>
                            <option value="12" {{ request('bulan', date('n')) == 12 ? 'selected' : '' }}>Desember</option>
                        </select>
                    </div>

                    {{-- Filter Tahun --}}
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Tahun</label>
                        <select name="tahun" class="form-select">
                            @for ($i = 2020; $i <= 2030; $i++)
                                <option value="{{ $i }}" {{ request('tahun', date('Y')) == $i ? 'selected' : '' }}>
                                    {{ $i }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    {{-- Tombol Terapkan --}}
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-filter me-1"></i> Terapkan
                        </button>
                    </div>

                </form>
            </div>
        </div>

        <!-- Tabel Approval Cuti -->
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle text-center">
                        <thead class="table-primary text-dark">
                            <tr>
                                <th>No</th>
                                <th>No ID</th>
                                <th>Departemen</th>
                                <th>Nama Karyawan</th>
                                <th>Nama Atasan</th>
                                <th>Jenis Cuti</th>
                                <th>Tanggal Pengajuan</th>
                                <th>Tanggal Mulai</th>
                                <th>Tanggal Selesai</th>
                                <th>Alasan</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($cutis as $cuti)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $cuti->user->badge_number ?? '-' }}</td>
                                    <td>{{ $cuti->user->departement ?? '-' }}</td>
                                    <td>{{ $cuti->user->name }}</td>
                                    <td>{{ $cuti->nama_atasan }}</td>
                                    <td>{{ $cuti->jenis_cuti }}</td>
                                    <td>{{ \Carbon\Carbon::parse($cuti->tgl_pengajuan)->format('d M Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($cuti->tgl_mulai)->format('d M Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($cuti->tgl_selesai)->format('d M Y') }}</td>
                                    <td>{{ Str::limit($cuti->alasan, 50) }}</td>
                                    <td>
                                        @if ($cuti->status_pengajuan == 'menunggu')
                                            <span class="badge bg-warning text-dark">Menunggu</span>
                                        @elseif($cuti->status_pengajuan == 'disetujui')
                                            <span class="badge bg-success">Disetujui</span>
                                        @elseif($cuti->status_pengajuan == 'ditolak')
                                            <span class="badge bg-danger">Ditolak</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($cuti->status_pengajuan == 'menunggu')
                                            <button class="btn btn-sm btn-info text-white me-1" 
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#detailModal" 
                                                    data-cuti='@json($cuti)'>
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-success me-1" 
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#approveModal" 
                                                    data-id="{{ $cuti->id }}"
                                                    data-nama="{{ $cuti->user->name }}">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger" 
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#rejectModal" 
                                                    data-id="{{ $cuti->id }}"
                                                    data-nama="{{ $cuti->user->name }}">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        @else
                                            <button class="btn btn-sm btn-secondary" 
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#detailModal" 
                                                    data-cuti='@json($cuti)'>
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="12" class="text-center text-muted py-3">Belum ada pengajuan cuti</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-3">
                    {{ $cutis->links() }}
                </div>
            </div>
        </div>

    </div>

    <!-- Modal Detail -->
    <div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title">Detail Pengajuan Cuti</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="mb-2"><strong>No ID:</strong> <span id="d_badge"></span></p>
                            <p class="mb-2"><strong>Nama Karyawan:</strong> <span id="d_nama"></span></p>
                            <p class="mb-2"><strong>Departemen:</strong> <span id="d_dept"></span></p>
                            <p class="mb-2"><strong>Nama Atasan:</strong> <span id="d_atasan"></span></p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Jenis Cuti:</strong> <span id="d_jenis"></span></p>
                            <p class="mb-2"><strong>Tanggal Pengajuan:</strong> <span id="d_pengajuan"></span></p>
                            <p class="mb-2"><strong>Tanggal Mulai:</strong> <span id="d_mulai"></span></p>
                            <p class="mb-2"><strong>Tanggal Selesai:</strong> <span id="d_selesai"></span></p>
                        </div>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <p class="mb-2"><strong>Alasan Cuti:</strong></p>
                        <p class="text-muted" id="d_alasan"></p>
                    </div>
                    <div class="mb-3">
                        <p class="mb-2"><strong>Status:</strong> <span id="d_status"></span></p>
                        <p class="mb-2"><strong>Komentar Admin:</strong> <span id="d_komentar" class="text-muted"></span></p>
                    </div>
                    <div class="mb-3">
                        <p class="mb-2"><strong>Tanda Tangan Karyawan:</strong></p>
                        <img id="d_ttd" src="" alt="Tanda Tangan" class="img-thumbnail" style="max-width: 300px;">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Approve -->
    <div class="modal fade" id="approveModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">Setujui Pengajuan Cuti</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="approveForm" method="POST">
                    @csrf
                    <div class="modal-body">
                        <p>Apakah Anda yakin ingin menyetujui pengajuan cuti dari <strong id="approve_nama"></strong>?</p>
                        <div class="mb-3">
                            <label class="form-label">Komentar (Opsional)</label>
                            <textarea name="komentar_admin" class="form-control" rows="3" placeholder="Tambahkan komentar jika diperlukan"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-check me-1"></i> Setujui
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Reject -->
    <div class="modal fade" id="rejectModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Tolak Pengajuan Cuti</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="rejectForm" method="POST">
                    @csrf
                    <div class="modal-body">
                        <p>Apakah Anda yakin ingin menolak pengajuan cuti dari <strong id="reject_nama"></strong>?</p>
                        <div class="mb-3">
                            <label class="form-label">Alasan Penolakan <span class="text-danger">*</span></label>
                            <textarea name="komentar_admin" class="form-control" rows="3" placeholder="Berikan alasan penolakan" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-times me-1"></i> Tolak
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        // Modal Detail
        const detailModal = document.getElementById('detailModal');
        detailModal.addEventListener('show.bs.modal', event => {
            const button = event.relatedTarget;
            const cuti = JSON.parse(button.getAttribute('data-cuti'));

            document.getElementById('d_badge').textContent = cuti.user.badge_number || '-';
            document.getElementById('d_nama').textContent = cuti.user.name;
            document.getElementById('d_dept').textContent = cuti.user.departement || '-';
            document.getElementById('d_atasan').textContent = cuti.nama_atasan;
            document.getElementById('d_jenis').textContent = cuti.jenis_cuti;
            document.getElementById('d_pengajuan').textContent = new Date(cuti.tgl_pengajuan).toLocaleDateString('id-ID');
            document.getElementById('d_mulai').textContent = new Date(cuti.tgl_mulai).toLocaleDateString('id-ID');
            document.getElementById('d_selesai').textContent = new Date(cuti.tgl_selesai).toLocaleDateString('id-ID');
            document.getElementById('d_alasan').textContent = cuti.alasan;

            // Status
            let statusText = '-';
            let statusClass = 'badge bg-secondary';
            if (cuti.status_pengajuan === 'menunggu') {
                statusText = 'Menunggu';
                statusClass = 'badge bg-warning text-dark';
            } else if (cuti.status_pengajuan === 'disetujui') {
                statusText = 'Disetujui';
                statusClass = 'badge bg-success';
            } else if (cuti.status_pengajuan === 'ditolak') {
                statusText = 'Ditolak';
                statusClass = 'badge bg-danger';
            }
            document.getElementById('d_status').innerHTML = `<span class="${statusClass}">${statusText}</span>`;
            document.getElementById('d_komentar').textContent = cuti.komentar_admin || '-';
            document.getElementById('d_ttd').src = cuti.tanda_tangan || '';
        });

        // Modal Approve
        const approveModal = document.getElementById('approveModal');
        approveModal.addEventListener('show.bs.modal', event => {
            const button = event.relatedTarget;
            const cutiId = button.getAttribute('data-id');
            const nama = button.getAttribute('data-nama');

            document.getElementById('approve_nama').textContent = nama;
            document.getElementById('approveForm').action = `/cuti/${cutiId}/approve`;
        });

        // Modal Reject
        const rejectModal = document.getElementById('rejectModal');
        rejectModal.addEventListener('show.bs.modal', event => {
            const button = event.relatedTarget;
            const cutiId = button.getAttribute('data-id');
            const nama = button.getAttribute('data-nama');

            document.getElementById('reject_nama').textContent = nama;
            document.getElementById('rejectForm').action = `/cuti/${cutiId}/reject`;
        });
    </script>
@endpush