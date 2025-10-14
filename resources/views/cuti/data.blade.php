@extends('layouts.app') {{-- Pastikan layout sb-admin2 sudah digunakan --}}

@section('title', 'Data Cuti')

@section('content')
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Data Cuti</h1>
        </div>

        <!-- Filter Section -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <form action="{{ route('cuti.data') }}" method="GET" class="row g-3 align-items-center">

                    <div class="col-md-3">
                        <label for="department" class="form-label small fw-bold">Department</label>
                        <select name="department" id="department" class="form-select">
                            <option value="">Semua</option>
                            <option value="Staff">Staff</option>
                            <option value="Supervisor">Supervisor</option>
                            <option value="Manager">Manager</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="status_pengajuan" class="form-label small fw-bold">Status Cuti</label>
                        <select name="status_pengajuan" id="status_pengajuan" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="Diajukan">Diajukan</option>
                            <option value="Disetujui">Disetujui</option>
                            <option value="Ditolak">Ditolak</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="bulan" class="form-label small fw-bold">Bulan</label>
                        <select name="bulan" id="bulan" class="form-select">
                            @foreach (['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'] as $b)
                                <option value="{{ $b }}">{{ $b }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="tahun" class="form-label small fw-bold">Tahun</label>
                        <select name="tahun" id="tahun" class="form-select">
                            @for ($y = now()->year; $y >= 2020; $y--)
                                <option value="{{ $y }}">{{ $y }}</option>
                            @endfor
                        </select>
                    </div>

                    <div class="col-md-12 d-flex justify-content-end mt-3">
                        <button type="submit" class="btn btn-primary btn-sm me-2">
                            <i class="fas fa-filter me-1"></i> Terapkan
                        </button>
                        <button type="button" class="btn btn-success btn-sm me-2">
                            <i class="fas fa-file-export me-1"></i> Export
                        </button>
                        <button type="button" class="btn btn-secondary btn-sm">
                            <i class="fas fa-file-import me-1"></i> Import
                        </button>
                    </div>

                </form>
            </div>
        </div>

        <!-- Table Section -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-primary text-center text-dark">
                            <tr>
                                <th>No</th>
                                <th>No ID</th>
                                <th>Departemen</th>
                                <th>Nama</th>
                                <th>Nama Atasan</th>
                                <th>Jenis Cuti</th>
                                <th>Tanggal Pengajuan</th>
                                <th>Mulai</th>
                                <th>Selesai</th>
                                <th>Alasan</th>
                                <th>Status</th>
                                <th>Tanggal Disetujui</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($cutis as $cuti)
                                @php $no=0; @endphp
                                <tr>
                                    <td>{{ ++$no }}</td>
                                    <td>{{ $cuti->user->badge_number }}</td>
                                    <td>{{ $cuti->user->departement }}</td>
                                    <td>{{ $cuti->user->name }}</td>
                                    <td>{{ $cuti->approver->name ?? '-' }}</td>
                                    <td>{{ $cuti->jenis_cuti }}</td>
                                    <td>{{ \Carbon\Carbon::parse($cuti->tanggal_pengajuan)->format('d/m/Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($cuti->tanggal_mulai)->format('d/m/Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($cuti->tanggal_selesai)->format('d/m/Y') }}</td>
                                    <td>{{ $cuti->alasan }}</td>
                                    <td>
                                        @if ($cuti->status_pengajuan == 'disetujui')
                                            <span class="badge bg-success">Disetujui</span>
                                        @elseif($cuti->status_pengajuan == 'menunggu')
                                            <span class="badge bg-warning text-dark">Menunggu</span>
                                        @elseif($cuti->status_pengajuan == 'diajukan')
                                            <span class="badge bg-warning text-dark">Diajukan</span>
                                        @else
                                            <span class="badge bg-danger">Ditolak</span>
                                        @endif
                                    </td>
                                    <td>{{ $cuti->tgl_status ? \Carbon\Carbon::parse($cuti->tgl_status)->format('d/m/Y') : '-' }}
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-info text-white btn-lihat"
                                            data-id="{{ $cuti->id }}">
                                            <i class="fas fa-eye"></i> Lihat
                                        </button>
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="13" class="text-center text-muted">Belum ada data cuti</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Lihat Detail Cuti -->
    <div class="modal fade" id="modalDetailCuti" tabindex="-1" aria-labelledby="detailCutiLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-bold" id="detailCutiLabel">
                        <i class="fas fa-file-alt me-2"></i> Detail Pengajuan Cuti
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered mb-0">
                        <tbody>
                            <tr>
                                <th width="30%">Nama Pegawai</th>
                                <td id="detail-nama"></td>
                            </tr>
                            <tr>
                                <th>No ID</th>
                                <td id="detail-badge"></td>
                            </tr>
                            <tr>
                                <th>Nama Atasan</th>
                                <td id="detail-atasan"></td>
                            </tr>

                            <tr>
                                <th>Jenis Cuti</th>
                                <td id="detail-jenis"></td>
                            </tr>
                            <tr>
                                <th>Tanggal Mulai</th>
                                <td id="detail-mulai"></td>
                            </tr>
                            <tr>
                                <th>Tanggal Selesai</th>
                                <td id="detail-selesai"></td>
                            </tr>
                            <tr>
                                <th>Lama Cuti</th>
                                <td id="detail-lama"></td>
                            </tr>
                            <tr>
                                <th>Alasan</th>
                                <td id="detail-alasan"></td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td id="detail-status"></td>
                            </tr>
                            <tr>
                                <th>Diajukan Pada</th>
                                <td id="detail-dibuat"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="btnCloseModal">
                        <i class="fas fa-times"></i> Tutup
                    </button>
                    <div id="action-buttons"></div>
                </div>
            </div>
        </div>
    </div>

@endsection
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modalElement = document.getElementById('modalDetailCuti');
        const modal = new bootstrap.Modal(modalElement);
        const buttons = document.querySelectorAll('.btn-lihat');
        const closeButton = document.getElementById('btnCloseModal');
        const actionDiv = document.getElementById('action-buttons');

        // ✅ Fungsi format tanggal (contoh: 10 Oktober 2025)
        function formatTanggal(dateString) {
            if (!dateString) return '-';
            const options = {
                day: '2-digit',
                month: 'long',
                year: 'numeric'
            };
            return new Date(dateString).toLocaleDateString('id-ID', options);
        }

        // ✅ Fungsi hitung lama cuti (dalam hari)
        function hitungLamaCuti(tglMulai, tglSelesai) {
            if (!tglMulai || !tglSelesai) return '-';
            const start = new Date(tglMulai);
            const end = new Date(tglSelesai);
            const selisihMs = end - start; // hasil dalam milidetik
            const lamaHari = Math.ceil(selisihMs / (1000 * 60 * 60 * 24)) + 1; // +1 agar inklusif
            return lamaHari > 0 ? lamaHari : 0; // pastikan tidak negatif
        }

        // ✅ Tutup modal
        function closeModal() {
            modal.hide();
        }

        // Klik tombol “Lihat”
        buttons.forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.dataset.id;

                fetch(`/cuti/${id}`)
                    .then(response => response.json())
                    .then(data => {
                        // Isi data ke modal
                        document.getElementById('detail-nama').textContent = data.user
                            ?.name ?? '-';
                        document.getElementById('detail-badge').textContent = data.user
                            ?.badge_number ?? '-';
                        document.getElementById('detail-atasan').textContent = data.approver
                            ?.name ?? '-';
                        document.getElementById('detail-jenis').textContent = data
                            .jenis_cuti ?? '-';
                        document.getElementById('detail-mulai').textContent = formatTanggal(
                            data.tgl_mulai);
                        document.getElementById('detail-selesai').textContent =
                            formatTanggal(data.tgl_selesai);

                        // ✅ Hitung lama cuti otomatis
                        const lamaCuti = hitungLamaCuti(data.tgl_mulai, data.tgl_selesai);
                        document.getElementById('detail-lama').textContent =
                            `${lamaCuti} hari`;

                        document.getElementById('detail-alasan').textContent = data
                            .alasan ?? '-';
                        document.getElementById('detail-dibuat').textContent =
                            formatTanggal(data.created_at);

                        // Status badge
                        let statusBadge = '';
                        switch (data.status_pengajuan) {
                            case 'disetujui':
                                statusBadge =
                                    '<span class="badge bg-success">Disetujui</span>';
                                break;
                            case 'ditolak':
                                statusBadge =
                                    '<span class="badge bg-danger">Ditolak</span>';
                                break;
                            default:
                                statusBadge =
                                    '<span class="badge bg-warning text-dark">Menunggu</span>';
                        }
                        document.getElementById('detail-status').innerHTML =
                            statusBadge;

                        // Tombol aksi (hanya jika status masih menunggu)
                        if (data.status_pengajuan === 'menunggu') {
                            actionDiv.innerHTML = `
                            <form method="POST" action="/cuti/${data.id}/approve" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-check"></i> Setujui
                                </button>
                            </form>
                            <form method="POST" action="/cuti/${data.id}/reject" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-times"></i> Tolak
                                </button>
                            </form>
                        `;
                        } else {
                            actionDiv.innerHTML = '';
                        }

                        // ✅ Tampilkan modal
                        modal.show();
                    })
                    .catch(error => {
                        console.error('❌ Gagal mengambil data cuti:', error);
                        alert('Terjadi kesalahan saat mengambil data.');
                    });
            });
        });

        // ✅ Tutup modal ketika tombol “Tutup” diklik
        closeButton.addEventListener('click', closeModal);
    });
</script>
