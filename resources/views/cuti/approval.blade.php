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
                        <select name="status" class="form-control">
                            <option value="">Semua Status</option>
                            <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Menunggu
                            </option>
                            <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui
                            </option>
                            <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                    </div>

                    {{-- Filter Bulan --}}
                    <div class="col-md-3">
                        <label for="bulan">Bulan</label>
                        <select class="form-control" id="bulan" name="bulan">
                            @foreach (['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'] as $namaBulan)
                                {{-- value diubah menjadi $loop->iteration (1, 2, 3, ...) --}}
                                <option value="{{ $loop->iteration }}" {{-- Logika 'selected' diubah untuk membandingkan angka (date('n')) --}}
                                    {{ request('bulan', date('n')) == $loop->iteration ? 'selected' : '' }}>

                                    {{ $namaBulan }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Filter Tahun --}}
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Tahun</label>
                        <select name="tahun" class="form-control">
                            @for ($i = 2020; $i <= 2030; $i++)
                                <option value="{{ $i }}"
                                    {{ request('tahun', date('Y')) == $i ? 'selected' : '' }}>
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
                                    <td>{{ $cuti->approver?->name }}</td>
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
                                            <button class="btn btn-sm btn-info text-white me-1" data-bs-toggle="modal"
                                                data-bs-target="#detailModal" data-cuti='@json($cuti)'>
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        @else
                                            <button class="btn btn-sm btn-secondary" data-bs-toggle="modal"
                                                data-bs-target="#detailModal" data-cuti='@json($cuti)'>
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
    <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">

                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="detailModalLabel">Detail Pengajuan Cuti</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <form id="formApprovalCuti" action="" method="POST">
                    @csrf
                    @method('POST')
                    <input type="hidden" id="app_cuti_id" name="cuti_id">

                    <div class="modal-body">
                        <!-- Bagian Detail Pengajuan (Tidak Berubah) -->
                        <h6 class="text-muted">Detail Pengajuan</h6>
                        <hr>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">No ID</label>
                                <input type="text" class="form-control" id="d_badge" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Departmen</label>
                                <input type="text" class="form-control" id="d_dept" readonly>
                            </div>
                        </div>
                        <!-- ... (baris nama, tanggal, alasan, dan ttd karyawan lainnya seperti sebelumnya) ... -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Nama Karyawan</label>
                                <input type="text" class="form-control" id="d_nama" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Nama Atasan yang menerima laporan</label>
                                <input type="text" class="form-control" id="detail-atasan" readonly>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label class="form-label">Jenis Cuti</label>
                                <input type="text" class="form-control" id="d_jenis" readonly>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Tanggal izin cuti</label>
                                <input type="text" class="form-control" id="d_pengajuan" readonly>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Start</label>
                                <input type="text" class="form-control" id="d_mulai" readonly>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">End</label>
                                <input type="text" class="form-control" id="d_selesai" readonly>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Alasan Izin Cuti</label>
                            <textarea class="form-control" id="d_alasan" rows="2" readonly></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tanda Tangan Karyawan</label>
                            <div>
                                <img id="d_ttd" src="" alt="TTD Karyawan" class="img-thumbnail bg-light"
                                    style="width: 200px; height: 125px; object-fit: contain;">
                            </div>
                        </div>


                        <h6 class="text-muted mt-4">Persetujuan Atasan</h6>
                        <hr>

                        <div id="approval-form-container">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label d-block">Status</label>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="status_pengajuan"
                                            id="status_disetujui" value="disetujui" required>
                                        <label class="form-check-label" for="status_disetujui">Disetujui</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="status_pengajuan"
                                            id="status_ditolak" value="ditolak" required>
                                        <label class="form-check-label" for="status_ditolak">Ditolak</label>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="d_komentar" class="form-label">Komentar</label>
                                <textarea class="form-control" id="d_komentar" name="komentar" rows="2" placeholder="Tulis komentar..."></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Tanda Tangan Atasan</label>
                                <div>
                                    <div class="border rounded bg-light p-3 d-flex justify-content-center">
                                        <canvas id="ttd_atasan_canvas" width="400" height="200"
                                            style="border:1px solid #ccc; background:white; border-radius:6px;"></canvas>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-secondary"
                                        id="clear_ttd_atasan">Hapus</button>
                                    <input type="hidden" name="ttd_atasan_base64" id="ttd_atasan_base64">
                                    <small class="text-muted d-block mt-1">Tanda tangan langsung di atas kotak, klik
                                        "Hapus"
                                        untuk mengulang.</small>
                                </div>
                                <input type="hidden" name="tanda_tangan_approval" id="tanda_tangan_approval">
                                <small class="text-muted d-block mt-1">Tanda tangan langsung di atas kotak, klik "Hapus"
                                    untuk mengulang.</small>
                            </div>
                        </div>

                        <!-- ============================================= -->
                        <!-- KONTENER UNTUK HASIL READ-ONLY                -->
                        <!-- ============================================= -->
                        <div id="approval-result-container" style="display: none;">
                            <div class="row">
                                <div class="col-md-12">
                                    <p class="mb-2"><strong>Status:</strong> <span id="res_status_badge"></span></p>
                                    <p class="mb-2"><strong>Komentar Atasan:</strong></p>
                                    <p id="res_komentar" class="text-muted fst-italic ps-3">Tidak ada komentar.</p>
                                    <p class="mb-2"><strong>Tanda Tangan Atasan:</strong></p>
                                    <img id="tanda_tangan_approval" src="" alt="tanda tangan approval"
                                        class="img-thumbnail bg-light"
                                        style="width: 200px; height: 125px; object-fit: contain;">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <!-- Submit -->
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary px-4">Simpan</button>
                    </div>
                    </div>
                </form>

            </div>
        </div>
    </div>



@endsection

<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
@push('scripts')

    <script>
        // TTD Approval
        document.addEventListener("DOMContentLoaded", function() {
            const canvas = document.getElementById('signature-pad');
            const ctx = canvas.getContext('2d');
            const clearBtn = document.getElementById('clear-signature');
            const input = document.getElementById('tanda_tangan_approval');
            let drawing = false;

            // Background putih
            ctx.fillStyle = "#fff";
            ctx.fillRect(0, 0, canvas.width, canvas.height);

            canvas.addEventListener('mousedown', (e) => {
                drawing = true;
                ctx.beginPath();
                ctx.moveTo(e.offsetX, e.offsetY);
            });
            canvas.addEventListener('mousemove', (e) => {
                if (!drawing) return;
                ctx.lineTo(e.offsetX, e.offsetY);
                ctx.strokeStyle = "#000";
                ctx.lineWidth = 2;
                ctx.stroke();
            });
            canvas.addEventListener('mouseup', () => {
                drawing = false;
                input.value = canvas.toDataURL('image/png');
            });
            clearBtn.addEventListener('click', () => {
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                ctx.fillStyle = "#fff";
                ctx.fillRect(0, 0, canvas.width, canvas.height);
                input.value = "";
            });
        });

        // Elemen-elemen untuk logika read-only
        const approvalFormContainer = document.getElementById('approval-form-container');
        const approvalResultContainer = document.getElementById('approval-result-container');
        const saveButton = document.getElementById('simpan-btn');
        let signaturePad;

        function resizeCanvas() {
            // Cek dulu apakah canvas terlihat, baru resize
            if (!canvas.offsetParent) return;
            const ratio = Math.max(window.devicePixelRatio || 1, 1);
            canvas.width = canvas.offsetWidth * ratio;
            canvas.height = canvas.offsetHeight * ratio;
            canvas.getContext("2d").scale(ratio, ratio);
            if (signaturePad) {
                signaturePad.clear();
            }
        }

        // --- EVENT LISTENER SAAT MODAL AKAN DITAMPILKAN ---
        detailModal.addEventListener('show.bs.modal', event => {
            const button = event.relatedTarget;
            const cuti = JSON.parse(button.getAttribute('data-cuti'));
            const form = document.getElementById('formApprovalCuti');
            // 1. ISI DATA DETAIL PENGAJUAN
            document.getElementById('d_badge').value = cuti.user.badge_number || '-';
            document.getElementById('d_nama').value = cuti.user.name;
            document.getElementById('d_dept').value = cuti.user.departement || '-';
            document.getElementById('detail-atasan').value = cuti.approver?.name ?? '-';
            document.getElementById('d_jenis').value = cuti.jenis_cuti;
            document.getElementById('d_pengajuan').value = new Date(cuti.tgl_pengajuan)
                .toLocaleDateString('id-ID');
            document.getElementById('d_mulai').value = new Date(cuti.tgl_mulai).toLocaleDateString(
                'id-ID');
            document.getElementById('d_selesai').value = new Date(cuti.tgl_selesai).toLocaleDateString(
                'id-ID');
            document.getElementById('d_alasan').value = cuti.alasan;
            document.getElementById('d_ttd').src = cuti.tanda_tangan ||
                'https://placehold.co/200x125?text=TTD+Tidak+Ada';

            // 2. APPROVAL (EDITABLE) ATAU DETAIL (READ-ONLY)
            if (cuti.status_pengajuan === 'menunggu') {
                // MODE APPROVAL (EDITABLE)
                approvalFormContainer.style.display = 'block';
                approvalResultContainer.style.display = 'none';
                saveButton.style.display = 'block';

                // Reset form approval
                form.action = `/cuti/${cuti.id}/process-approval`; // Ganti dengan URL route Anda
                document.getElementById('app_cuti_id').value = cuti.id;
                document.getElementById('d_komentar').value = '';
                document.querySelectorAll('input[name="status_pengajuan"]').forEach(radio => radio
                    .checked = false);

            } else {
                // MODE DETAIL (READ-ONLY)
                approvalFormContainer.style.display = 'none';
                approvalResultContainer.style.display = 'block';
                saveButton.style.display = 'none';

                // Tampilkan hasil approval
                let statusText = cuti.status_pengajuan.charAt(0).toUpperCase() + cuti.status_pengajuan
                    .slice(1);
                let statusClass = 'badge bg-secondary';
                if (cuti.status_pengajuan === 'disetujui') statusClass = 'badge bg-success';
                if (cuti.status_pengajuan === 'ditolak') statusClass = 'badge bg-danger';
                document.getElementById('res_status_badge').innerHTML =
                    `<span class="${statusClass}">${statusText}</span>`;

                document.getElementById('res_komentar').textContent = cuti.komentar ||
                    'Tidak ada komentar.';
                document.getElementById('tanda_tangan_approval').src = cuti.tanda_tangan_approval ||
                    'https://placehold.co/200x125?text=TTD+Tidak+Ada';
            }
        });


                if (cuti.status_pengajuan === 'menunggu') {
                    // MODE APPROVAL (EDITABLE)
                    approvalFormContainer.style.display = 'block';
                    approvalResultContainer.style.display = 'none';
                    saveButton.style.display = 'block';

                    // Reset form approval
                    form.action = `/cuti/${cuti.id}/process-approval`; // Ganti dengan URL route Anda
                    document.getElementById('app_cuti_id').value = cuti.id;
                    document.getElementById('d_komentar').value = '';
                    document.querySelectorAll('input[name="status_pengajuan"]').forEach(radio => radio
                        .checked = false);

                } else {
                    // MODE DETAIL (READ-ONLY)
                    approvalFormContainer.style.display = 'none';
                    approvalResultContainer.style.display = 'block';
                    saveButton.style.display = 'none';

                    // Tampilkan hasil approval
                    let statusText = cuti.status_pengajuan.charAt(0).toUpperCase() + cuti.status_pengajuan
                        .slice(1);
                    let statusClass = 'badge bg-secondary';
                    if (cuti.status_pengajuan === 'disetujui') statusClass = 'badge bg-success';
                    if (cuti.status_pengajuan === 'ditolak') statusClass = 'badge bg-danger';
                    document.getElementById('res_status_badge').innerHTML =
                        `<span class="${statusClass}">${statusText}</span>`;

                    document.getElementById('res_komentar').textContent = cuti.komentar ||
                        'Tidak ada komentar.';
                    document.getElementById('res_ttd_atasan').src = cuti.tanda_tangan_approval ?
               `/storage/${cuti.tanda_tangan_approval}` :
                'https://placehold.co/200x125?text=TTD+Tidak+Ada';
                }
            });

        clearButton.addEventListener('click', () => {
            if (signaturePad) {
                signaturePad.clear();
                hiddenInput.value = '';
            }
        });

        detailModal.addEventListener('hidden.bs.modal', () => {
            if (signaturePad) {
                signaturePad.clear();
                hiddenInput.value = '';
                signaturePad.off();
                signaturePad = null;
            }
        });

        window.addEventListener("resize", resizeCanvas);

    </script>
@endpush
