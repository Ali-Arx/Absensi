@extends('layouts.app')

@section('title', 'Pengaturan Sistem')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Pengaturan</h1>

    <div class="card shadow p-4">
        <form id="formSetting">
            @csrf
            <h5 class="mb-3 text-danger"><i class="fas fa-clock"></i> Waktu Kerja</h5>
            <div class="row mb-4">
                <div class="col-md-4">
                    <label>Jam Masuk Default</label>
                    <input type="time" class="form-control" name="jam_masuk_default" value="{{ $setting->jam_masuk_default ?? '' }}">
                </div>
                <div class="col-md-4">
                    <label>Jam Pulang Default</label>
                    <input type="time" class="form-control" name="jam_pulang_default" value="{{ $setting->jam_pulang_default ?? '' }}">
                </div>
                <div class="col-md-4">
                    <label>Durasi Istirahat (Menit)</label>
                    <input type="number" class="form-control" name="durasi_istirahat" value="{{ $setting->durasi_istirahat ?? '' }}">
                </div>
            </div>

            <hr>

            <h5 class="mb-3 text-danger"><i class="fas fa-calendar-alt"></i> Batas Cuti Tahunan</h5>
            <div class="row mb-4">
                <div class="col-md-4">
                    <label>Maksimal Cuti / Tahun</label>
                    <input type="number" class="form-control" name="maks_cuti_tahun" value="{{ $setting->maks_cuti_tahun ?? '' }}">
                </div>
                <div class="col-md-4">
                    <label>Maksimal Cuti / Bulan</label>
                    <input type="number" class="form-control" name="maks_cuti_bulan" value="{{ $setting->maks_cuti_bulan ?? '' }}">
                </div>
                <div class="col-md-4">
                    <label>Cuti Minimal Sebelum Pengajuan</label>
                    <input type="number" class="form-control" name="cuti_minimal_sebelum_pengajuan" value="{{ $setting->cuti_minimal_sebelum_pengajuan ?? '' }}">
                </div>
            </div>

            <hr>

            <h5 class="mb-3 text-danger"><i class="fas fa-shield-alt"></i> Kebijakan Sistem</h5>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="password_min_8" {{ ($setting->password_min_8 ?? false) ? 'checked' : '' }}>
                <label class="form-check-label">Password Minimal 8 Karakter</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="selfie_absensi" {{ ($setting->selfie_absensi ?? false) ? 'checked' : '' }}>
                <label class="form-check-label">Selfie Realtime Saat Absensi</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="verifikasi_gps" {{ ($setting->verifikasi_gps ?? false) ? 'checked' : '' }}>
                <label class="form-check-label">Verifikasi Lokasi GPS Aktif</label>
            </div>
            <div class="form-check mb-4">
                <input class="form-check-input" type="checkbox" name="export_mingguan" {{ ($setting->export_mingguan ?? false) ? 'checked' : '' }}>
                <label class="form-check-label">Export Laporan Mingguan</label>
            </div>

            <div class="d-flex justify-content-end mt-3">
                <button type="button" class="btn btn-secondary me-2" id="btnReset"><i class="fas fa-undo"></i> Kembalikan Default</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$('#formSetting').on('submit', function(e) {
    e.preventDefault();
    $.ajax({
        url: "{{ route('setting.update') }}",
        type: "POST",
        data: $(this).serialize(),
        success: function() {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Pengaturan berhasil disimpan.',
                timer: 1500,
                showConfirmButton: false
            });
        },
        error: function() {
            Swal.fire('Gagal', 'Terjadi kesalahan saat menyimpan.', 'error');
        }
    });
});

$('#btnReset').click(function() {
    Swal.fire({
        title: 'Kembalikan ke default?',
        text: "Semua pengaturan akan dihapus!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, kembalikan!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post("{{ route('setting.reset') }}", {_token: '{{ csrf_token() }}'}, function() {
                Swal.fire('Berhasil!', 'Pengaturan dikembalikan ke default.', 'success');
                setTimeout(() => location.reload(), 1500);
            });
        }
    });
});
</script>
@endpush
