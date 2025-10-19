<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">Data Kehadiran Karyawan</h6>
        <a href="#" class="btn btn-sm btn-primary"><i class="fas fa-download"></i> Export</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-light text-center">
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Departemen</th>
                        <th>Tanggal</th>
                        <th>Jam Masuk</th>
                        <th>Jam Pulang</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $index => $item)
                        <tr class="text-center">
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item['name'] }}</td>
                            <td>{{ $item['departement'] }}</td>
                            <td>{{ $item['tanggal'] }}</td>
                            <td>{{ $item['jam_masuk'] }}</td>
                            <td>{{ $item['jam_pulang'] }}</td>
                            <td>
                                @switch($item['status'])
                                    @case('Hadir')
                                        <span class="badge bg-success">Hadir</span>
                                    @break

                                    @case('Hadir (Terlambat)')
                                        <span class="badge bg-warning text-dark">Hadir (Terlambat)</span>
                                    @break

                                    @case('Cuti')
                                        <span class="badge bg-info text-dark">Cuti</span>
                                    @break

                                    @case('Lembur')
                                        <span class="badge bg-primary">Lembur</span>
                                    @break

                                    @default
                                        <span class="badge bg-danger">Tidak Hadir</span>
                                @endswitch

                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>
</div>
