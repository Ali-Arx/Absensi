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
                    {{-- Loop data dari controller --}}
                    @forelse ($data as $index => $item)
                        <tr class="text-center">
                            <td>{{ $index + 1 }}</td>

                            {{-- Data Karyawan --}}
                            <td class="text-start">{{ $item['name'] }}</td>
                            <td>{{ $item['departement'] ?? '-' }}</td>
                            <td>{{ $item['tanggal'] }}</td>

                            {{-- Jam Masuk/Pulang --}}
                            <td>{{ $item['jam_masuk'] }}</td>
                            <td>{{ $item['jam_pulang'] }}</td>

                            {{-- Kolom Status dengan Badge (dari kode Anda) --}}
                            <td class="text-center">
                                @php $status = $item['status']; @endphp

                                {{-- 1. Status Absensi --}}
                                @if ($status == 'Hadir')
                                    <span class="badge bg-success">Hadir</span>
                                @elseif ($status == 'Hadir (Terlambat)')
                                    <span class="badge bg-warning text-dark">Hadir (Terlambat)</span>
                                @elseif ($status == 'Tidak Hadir')
                                    <span class="badge bg-danger">Tidak Hadir</span>

                                    {{-- 2. Status Cuti (Aktif atau Pengajuan) --}}
                                @elseif (Str::startsWith($status, 'Cuti'))
                                    @if ($status == 'Cuti (Disetujui)')
                                        <span class="badge bg-info text-dark">{{ $status }}</span>
                                    @elseif ($status == 'Cuti (Ditolak)')
                                        <span class="badge bg-danger">{{ $status }}</span>
                                    @else
                                        {{-- Ini akan menangkap 'Cuti (Menunggu)' atau status custom lainnya --}}
                                        <span class="badge bg-secondary">{{ $status }}</span>
                                    @endif

                                    {{-- 3. Status Lembur (Aktif atau Pengajuan) --}}
                                @elseif (Str::startsWith($status, 'Lembur'))
                                    @if ($status == 'Lembur (Disetujui)')
                                        <span class="badge bg-primary">{{ $status }}</span>
                                    @elseif ($status == 'Lembur (Ditolak)')
                                        <span class="badge bg-danger">{{ $status }}</span>
                                    @else
                                        {{-- Ini akan menangkap 'Lembur (Menunggu)' --}}
                                        <span class="badge bg-secondary">{{ $status }}</span>
                                    @endif
                                @else
                                    {{-- Fallback jika ada status aneh --}}
                                    <span class="badge bg-dark">{{ $status }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        {{-- Tampilkan ini jika tidak ada user di database --}}
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada data karyawan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        </div>
    </div>
</div>
