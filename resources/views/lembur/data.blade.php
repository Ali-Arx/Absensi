@extends('layouts.app')

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Data Lembur</h1>
        </div>

        {{-- Menampilkan Pesan Sukses (dari 'with('success', ...)') --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-1"></i>
                {!! session('success') !!}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Menampilkan Pesan Error (dari 'with('error', ...)') --}}
        {{-- Ini adalah pesan yang akan menangkap error dari controller Anda --}}
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-1"></i>
                <strong>Gagal!</strong><br>
                {!! session('error') !!} {{-- Kita pakai {!! !!} agar <br> dari error validasi bisa tampil --}}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Menampilkan Error Validasi Bawaan Laravel (misal: 'file' => 'required|mimes:...') --}}
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-1"></i>
                <strong>Error Validasi!</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Filter Card -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <form action="{{ route('lembur.data') }}" method="GET">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="department">Department</label>
                                <select name="department" id="department" class="form-control">
                                    <option value="">Semua</option>

                                    {{-- Ganti <option> statis Anda dengan loop ini --}}
                                    @foreach ($departments as $dept)
                                        <option value="{{ $dept }}"
                                            {{ request('department') == $dept ? 'selected' : '' }}>
                                            {{ $dept }}
                                        </option>
                                    @endforeach

                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select name="status" class="form-control">
                                    <option value="">Semua Status</option>
                                    <option value="menunggu"
                                        {{ request('status_pengajuan') == 'menunggu' ? 'selected' : '' }}>
                                        Menunggu</option>
                                    <option value="disetujui"
                                        {{ request('status_pengajuan') == 'disetujui' ? 'selected' : '' }}>
                                        Disetujui
                                    </option>
                                    <option value="ditolak"
                                        {{ request('status_pengajuan') == 'ditolak' ? 'selected' : '' }}>
                                        Ditolak
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="month">Bulan</label>
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
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="year">Tahun</label>
                                <select name="tahun" class="form-control">
                                    @for ($i = 2020; $i <= 2030; $i++)
                                        <option value="{{ $i }}"
                                            {{ request('tahun', date('Y')) == $i ? 'selected' : '' }}>
                                            {{ $i }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-filter"></i> Terapkan Filter
                            </button>
                            <a href="{{ route('lembur.data') }}" class="btn btn-secondary">
                                <i class="fas fa-redo"></i> Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Action Buttons & Search -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-4">
                        <a href="{{ route('lembur.export.data', request()->query()) }}"
                            class="btn btn-success btn-sm mr-2">
                            <i class="fas fa-file-excel"></i> Export
                        </a>
                        <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#importLemburModal">
                            <i class="fas fa-file-upload"></i> Import
                        </button>
                    </div>
                    <div class="col-md-2 text-center">
                        <button class="btn btn-outline-secondary btn-sm" title="List View">
                            <i class="fas fa-th-list"></i>
                        </button>
                        <button class="btn btn-outline-secondary btn-sm" title="Grid View">
                            <i class="fas fa-th"></i>
                        </button>
                    </div>
                    <div class="col-md-6">
                        <form action="{{ route('lembur.data') }}" method="GET">
                            <div class="input-group">
                                <input type="text" class="form-control" name="search"
                                    placeholder="Cari nama atau job description..." value="{{ request('search') }}">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Table Card -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Data Lembur</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                        <thead class="thead-light">
                            <tr>
                                <th>No</th>
                                <th>Name</th>
                                <th>Plan</th>
                                <th>Actual</th>
                                <th>Paraf</th>
                                <th>OT Hours</th>
                                <th>Spv</th>
                                <th>Job Description</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($lemburs ?? [] as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $item->user->name ?? '-' }}</td>
                                    <td>{{ $item->tgl_jam_mulai ?? '-' }}</td>
                                    <td>{{ $item->tgl_jam_selesai ?? '-' }}</td>
                                    @php
                                        $statusText = ['approved', 'rejected', 'pending'];
                                    @endphp

                                    <td class="text-center">
                                        @if ($item->tanda_tangan)
                                            @if (in_array(strtolower($item->tanda_tangan), $statusText))
                                                {{ ucfirst($item->tanda_tangan) }}
                                            @else
                                                <img src="{{ asset('storage/' . $item->tanda_tangan) }}"
                                                    alt="Tanda Tangan" style="width: 80px; height: auto;">
                                            @endif
                                        @else
                                            -
                                        @endif
                                    </td>


                                    <td>{{ $item->total_jam_kerja ?? '-' }}</td>
                                    <td>{{ $item->approver ? $item->approver->name : '-' }}</td>
                                    <td>{{ $item->deskripsi_kerja ?? '-' }}</td>
                                    <td>
                                        @if ($item->status_pengajuan == 'disetujui')
                                            <span class="badge badge-success">Disetujui</span>
                                        @elseif($item->status_pengajuan == 'ditolak')
                                            <span class="badge badge-danger">Ditolak</span>
                                        @elseif($item->status_pengajuan == 'menunggu')
                                            <span class="badge badge-warning">Menunggu</span>
                                        @else
                                            <span class="badge badge-warning">Pending</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-info btn-sm btn-detail"
                                            data-id="{{ $item->id }}">
                                            <i class="fas fa-eye"></i> Detail
                                        </button>
                                    </td>


                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11" class="text-center">Tidak ada data riwayat lembur</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <!-- Modal Detail -->
                    <div class="modal fade" id="modalDetail" tabindex="-1" aria-labelledby="modalDetailLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header bg-primary text-white">
                                    <h5 class="modal-title" id="modalDetailLabel">Detail Pengajuan Lembur</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th>Nama</th>
                                            <td id="detail-name"></td>
                                        </tr>
                                        <tr>
                                            <th>Tanggal Pengajuan</th>
                                            <td id="detail-tgl"></td>
                                        </tr>
                                        <tr>
                                            <th>Jam Mulai</th>
                                            <td id="detail-mulai"></td>
                                        </tr>
                                        <tr>
                                            <th>Jam Selesai</th>
                                            <td id="detail-selesai"></td>
                                        </tr>
                                        <tr>
                                            <th>Total Jam</th>
                                            <td id="detail-total"></td>
                                        </tr>
                                        <tr>
                                            <th>Approver</th>
                                            <td id="detail-approver"></td>
                                        </tr>
                                        <tr>
                                            <th>Deskripsi Kerja</th>
                                            <td id="detail-deskripsi"></td>
                                        </tr>
                                        <tr>
                                            <th>Status</th>
                                            <td id="detail-status"></td>
                                        </tr>
                                        <tr>
                                            <th>Tanda Tangan</th>
                                            <td id="detail-paraf"></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pagination -->
                @if (isset($lembur) && $lembur->hasPages())
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            Showing {{ $lembur->firstItem() }} to {{ $lembur->lastItem() }} of {{ $lembur->total() }}
                            entries
                        </div>
                        <div>
                            {{ $lembur->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>

    </div>
    <!-- /.container-fluid -->

    <!-- Import Modal -->
    <div class="modal fade" id="importLemburModal" tabindex="-1" aria-labelledby="importLemburModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('lembur.import.data') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="importLemburModalLabel">Import Data Lembur</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="fileImportLembur" class="form-label">Pilih file (Excel .xlsx):</label>
                            <input type="file" name="file" id="fileImportLembur" class="form-control" required>
                        </div>
                        <div class="alert alert-info p-2">
                            <small>
                                <i class="fas fa-info-circle me-1"></i>
                                <strong>Template:</strong> Pastikan file memiliki header yang sesuai,
                                seperti: `no_id_karyawan`, `tgl_pengajuan`, `tgl_jam_mulai`,
                                `tgl_jam_selesai`, `nama_atasan`, `status_pengajuan`, `deskripsi_kerja`,
                                `section`, `total_jam_kerja`, `tgl_status`.
                            </small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-file-import me-1"></i> Import & Update
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $(document).on('click', '.btn-detail', function() {
                const id = $(this).data('id');

                $.ajax({
                    url: "{{ url('/lembur/detail') }}/" + id,
                    method: "GET",
                    beforeSend: function() {
                        $('#modalDetail').modal('show');
                        $('#modalDetail table').addClass('opacity-50');
                        $('#modalDetail .modal-body').prepend(
                            '<p class="text-center text-muted" id="loading-text">Loading...</p>'
                        );
                    },
                    success: function(response) {
                        $('#loading-text').remove();
                        $('#modalDetail table').removeClass('opacity-50');
                        if (response.success) {
                            const data = response.data;
                            console.log("Isi data:", data);

                            $('#detail-name').text(data.user?.name ?? '-');
                            $('#detail-tgl').text(data.tgl_pengajuan ?? '-');
                            $('#detail-mulai').text(data.tgl_jam_mulai ?? '-');
                            $('#detail-selesai').text(data.tgl_jam_selesai ?? '-');
                            $('#detail-total').text(data.total_jam_kerja ?? '-');
                            $('#detail-approver').text(data.approver?.name ?? '-');
                            $('#detail-deskripsi').text(data.deskripsi_kerja ?? '-');
                            $('#detail-status').html(formatStatus(data.status_pengajuan));

                            if (data.tanda_tangan) {
                                // Jika isinya teks status (Approved / Rejected)
                                if (['approved', 'rejected'].includes(data.tanda_tangan
                                        .toLowerCase())) {
                                    $('#detail-paraf').text(data.tanda_tangan);
                                } else {
                                    // Jika isinya path file gambar
                                    $('#detail-paraf').html(
                                        '<img src="/storage/' + data.tanda_tangan +
                                        '" alt="Tanda Tangan" style="width: 100px; height: auto;">'
                                    );
                                }
                            } else {
                                $('#detail-paraf').text('-');
                            }

                        } else {
                            $('#modalDetail .modal-body').html(
                                '<p class="text-danger text-center">Data tidak ditemukan.</p>'
                            );
                        }
                    },
                    error: function(xhr) {
                        console.error("Terjadi error:", xhr.responseText);
                        $('#modalDetail .modal-body').html(
                            '<p class="text-danger text-center">Terjadi kesalahan saat memuat data.</p>'
                        );
                    }
                });

            });

            function formatStatus(status) {
                if (status === 'disetujui') return '<span class="badge bg-success">Disetujui</span>';
                if (status === 'ditolak') return '<span class="badge bg-danger">Ditolak</span>';
                return '<span class="badge bg-warning text-dark">Menunggu</span>';
            }
        });
    </script>
@endpush
