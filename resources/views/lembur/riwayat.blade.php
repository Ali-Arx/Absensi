@extends('layouts.app')

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-4 text-gray-800">Riwayat Lembur</h1>

        <!-- Filter Card -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('lembur.riwayat') }}">
                    <div class="row">
                        <!-- Date Filter -->
                        <div class="col-md-3 mb-3">
                            <label for="date" class="form-label">Date</label>
                            <input type="date" class="form-control" id="date" name="date"
                                value="{{ request('date') }}">
                        </div>

                        <!-- Status Filter -->
                        <div class="col-md-2 mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-control" id="status" name="status">
                                <option value="">Semua Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending
                                </option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved
                                </option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected
                                </option>
                            </select>
                        </div>

                        <!-- Month Filter -->
                        <div class="col-md-2 mb-3">
                            <label for="bulan" class="form-label">Bulan</label>
                            <select class="form-control" id="bulan" name="bulan">
                                <option value="">Pilih Bulan</option>
                                <option value="1" {{ request('bulan') == '1' ? 'selected' : '' }}>Januari</option>
                                <option value="2" {{ request('bulan') == '2' ? 'selected' : '' }}>Februari</option>
                                <option value="3" {{ request('bulan') == '3' ? 'selected' : '' }}>Maret</option>
                                <option value="4" {{ request('bulan') == '4' ? 'selected' : '' }}>April</option>
                                <option value="5" {{ request('bulan') == '5' ? 'selected' : '' }}>Mei</option>
                                <option value="6" {{ request('bulan') == '6' ? 'selected' : '' }}>Juni</option>
                                <option value="7" {{ request('bulan') == '7' ? 'selected' : '' }}>Juli</option>
                                <option value="8" {{ request('bulan') == '8' ? 'selected' : '' }}>Agustus</option>
                                <option value="9" {{ request('bulan') == '9' ? 'selected' : '' }}>September</option>
                                <option value="10" {{ request('bulan') == '10' ? 'selected' : '' }}>Oktober</option>
                                <option value="11" {{ request('bulan') == '11' ? 'selected' : '' }}>November</option>
                                <option value="12" {{ request('bulan') == '12' ? 'selected' : '' }}>Desember</option>
                            </select>
                        </div>

                        <!-- Year Filter -->
                        <div class="col-md-2 mb-3">
                            <label for="tahun" class="form-label">Tahun</label>
                            <select class="form-control" id="tahun" name="tahun">
                                <option value="">Pilih Tahun</option>
                                @for ($year = date('Y'); $year >= 2020; $year--)
                                    <option value="{{ $year }}" {{ request('tahun') == $year ? 'selected' : '' }}>
                                        {{ $year }}</option>
                                @endfor
                            </select>
                        </div>

                        <!-- Submit Button -->
                        <div class="col-md-3 mb-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-filter"></i> Filter
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Data Table Card -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Data Riwayat Lembur</h6>
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
    <div class="modal fade" id="modalDetail" tabindex="-1" aria-labelledby="modalDetailLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalDetailLabel">Detail Pengajuan Lembur</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Initialize DataTable (optional - if you want client-side features)
            // $('#dataTable').DataTable({
            //     "paging": false,
            //     "searching": false,
            //     "info": false
            // });

            // Auto-submit on filter change (optional)
            $('#status, #bulan, #tahun').change(function() {
                $(this).closest('form').submit();
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

@push('styles')
    <style>
        .table thead th {
            vertical-align: middle;
            text-align: center;
        }

        .table tbody td {
            vertical-align: middle;
        }

        .badge {
            padding: 0.5em 0.75em;
        }
    </style>
@endpush
