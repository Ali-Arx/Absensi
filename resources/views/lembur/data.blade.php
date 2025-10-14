@extends('layouts.app')

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Data Lembur</h1>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <!-- Filter Card -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <form action="{{ route('lembur.data') }}" method="GET">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="date">Date</label>
                                <input type="date" class="form-control" id="date" name="date"
                                    value="{{ request('date') }}">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="department">Department</label>
                                <select class="form-control" id="department" name="department">
                                    <option value="">Semua Department</option>
                                    <option value="HR" {{ request('department') == 'HR' ? 'selected' : '' }}>HR</option>
                                    <option value="IT" {{ request('department') == 'IT' ? 'selected' : '' }}>IT</option>
                                    <option value="Finance" {{ request('department') == 'Finance' ? 'selected' : '' }}>
                                        Finance</option>
                                    <option value="Marketing" {{ request('department') == 'Marketing' ? 'selected' : '' }}>
                                        Marketing</option>
                                    <option value="Operations"
                                        {{ request('department') == 'Operations' ? 'selected' : '' }}>Operations</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select class="form-control" id="status" name="status">
                                    <option value="">Semua Status</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending
                                    </option>
                                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>
                                        Approved</option>
                                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>
                                        Rejected</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="month">Bulan</label>
                                <select class="form-control" id="month" name="month">
                                    <option value="">Semua Bulan</option>
                                    <option value="Januari" {{ request('month') == 'Januari' ? 'selected' : '' }}>Januari
                                    </option>
                                    <option value="Februari" {{ request('month') == 'Februari' ? 'selected' : '' }}>
                                        Februari</option>
                                    <option value="Maret" {{ request('month') == 'Maret' ? 'selected' : '' }}>Maret
                                    </option>
                                    <option value="April" {{ request('month') == 'April' ? 'selected' : '' }}>April
                                    </option>
                                    <option value="Mei" {{ request('month') == 'Mei' ? 'selected' : '' }}>Mei</option>
                                    <option value="Juni" {{ request('month') == 'Juni' ? 'selected' : '' }}>Juni</option>
                                    <option value="Juli" {{ request('month') == 'Juli' ? 'selected' : '' }}>Juli</option>
                                    <option value="Agustus" {{ request('month') == 'Agustus' ? 'selected' : '' }}>Agustus
                                    </option>
                                    <option value="September" {{ request('month') == 'September' ? 'selected' : '' }}>
                                        September</option>
                                    <option value="Oktober" {{ request('month') == 'Oktober' ? 'selected' : '' }}>Oktober
                                    </option>
                                    <option value="November" {{ request('month') == 'November' ? 'selected' : '' }}>
                                        November</option>
                                    <option value="Desember" {{ request('month') == 'Desember' ? 'selected' : '' }}>
                                        Desember</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="year">Tahun</label>
                                <select class="form-control" id="year" name="year">
                                    <option value="">Semua Tahun</option>
                                    <option value="2023" {{ request('year') == '2023' ? 'selected' : '' }}>2023</option>
                                    <option value="2024" {{ request('year') == '2024' ? 'selected' : '' }}>2024</option>
                                    <option value="2025" {{ request('year') == '2025' ? 'selected' : '' }}>2025</option>
                                    <option value="2026" {{ request('year') == '2026' ? 'selected' : '' }}>2026</option>
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
                        <a href="" class="btn btn-success btn-sm mr-2">
                            <i class="fas fa-file-excel"></i> Export
                        </a>
                        <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#importModal">
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
                                    <td class="text-center">
                                        @if ($item->tanda_tangan)
                                            <img src="{{ asset('storage/' . $item->tanda_tangan) }}" alt="Tanda Tangan"
                                                style="width: 80px; height: auto;">
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
                                        @elseif($item->status == 'ditolak')
                                            <span class="badge badge-danger">Ditolak</span>
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
    <div class="modal fade" id="importModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Import Data Lembur</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form action="" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>File Excel</label>
                            <input type="file" class="form-control-file" name="file" accept=".xlsx,.xls" required>
                            <small class="text-muted">Format: .xlsx atau .xls</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Import</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('.btn-detail').on('click', function() {
                var id = $(this).data('id');
                $.ajax({
                    url: '/lembur/' + id,
                    method: 'GET',
                    success: function(data) {
                        $('#detail-name').text(data.user.name || '-');
                        $('#detail-tgl').text(data.tgl_pengajuan || '-');
                        $('#detail-mulai').text(data.tgl_jam_mulai || '-');
                        $('#detail-selesai').text(data.tgl_jam_selesai || '-');
                        $('#detail-total').text(data.total_jam_kerja || '-');
                        $('#detail-approver').text(data.approver ? data.approver.name : '-');
                        $('#detail-deskripsi').text(data.deskripsi_kerja || '-');

                        var statusBadge = '';
                        if (data.status_pengajuan === 'disetujui') {
                            statusBadge = '<span class="badge badge-success">Disetujui</span>';
                        } else if (data.status_pengajuan === 'ditolak') {
                            statusBadge = '<span class="badge badge-danger">Ditolak</span>';
                        } else {
                            statusBadge = '<span class="badge badge-warning">Pending</span>';
                        }
                        $('#detail-status').html(statusBadge);

                        if (data.tanda_tangan) {
                            $('#detail-paraf').html('<img src="/storage/' + data.tanda_tangan +
                                '" alt="Tanda Tangan" style="width: 100px; height: auto;">');
                        } else {
                            $('#detail-paraf').text('-');
                        }

                        $('#modalDetail').modal('show');
                    },
                });
            });
        });

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
                                $('#detail-paraf').html(
                                    `<img src="{{ asset('storage') }}/${data.tanda_tangan}" 
                       alt="Tanda Tangan" 
                       style="width:120px; height:auto; border:1px solid #ccc; border-radius:5px;">`
                                );
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
