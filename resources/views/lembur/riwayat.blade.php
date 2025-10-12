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
                        <input type="date" class="form-control" id="date" name="date" value="{{ request('date') }}">
                    </div>

                    <!-- Status Filter -->
                    <div class="col-md-2 mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-control" id="status" name="status">
                            <option value="">Semua Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
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
                            @for($year = date('Y'); $year >= 2020; $year--)
                                <option value="{{ $year }}" {{ request('tahun') == $year ? 'selected' : '' }}>{{ $year }}</option>
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
            <div>
                <button class="btn btn-sm btn-outline-secondary" title="Sort">
                    <i class="fas fa-sort"></i>
                </button>
                <button class="btn btn-sm btn-outline-secondary" title="List View">
                    <i class="fas fa-list"></i>
                </button>
            </div>
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
                            <th>Absen<br><small>(Fill by Admin)</small></th>
                            <th>OT Hours</th>
                            <th>Spv</th>
                            <th>Job Description</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($lembur ?? [] as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->user->name ?? '-' }}</td>
                            <td>{{ $item->tgl_jam_mulai ?? '-' }}</td>
                            <td>{{ $item->tgl_jam_selesai ?? '-' }}</td>
                            <td>{{ $item->tanda_tangan ?? '-' }}</td>
                            <td>{{ $item->absen ?? '-' }}</td>
                            <td>{{ $item->ot_hours ?? '-' }}</td>
                            <td>{{ $item->spv ?? '-' }}</td>
                            <td>{{ $item->job_description ?? '-' }}</td>
                            <td>
                                @if($item->status == 'approved')
                                    <span class="badge badge-success">Approved</span>
                                @elseif($item->status == 'rejected')
                                    <span class="badge badge-danger">Rejected</span>
                                @else
                                    <span class="badge badge-warning">Pending</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('lembur.detail', $item->id) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="11" class="text-center">Tidak ada data riwayat lembur</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if(isset($lembur) && $lembur->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div>
                    Showing {{ $lembur->firstItem() }} to {{ $lembur->lastItem() }} of {{ $lembur->total() }} entries
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