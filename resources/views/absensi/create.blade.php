@extends('layouts.app')

@section('content')
    <div class="container-fluid">

        {{-- Form Absensi --}}
        <div class="card shadow-sm border-0" style="margin-top: 15px;">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-4">Form Absensi</h5>

                {{-- Notifikasi --}}
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>✓ Berhasil!</strong> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>✗ Error!</strong> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>✗ Validasi Gagal!</strong>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form method="POST" action="{{ route('absensi.store') }}" enctype="multipart/form-data">
                    @csrf

                    {{-- Baris 1: Pilih Absen, Tanggal, Pilih Shift, Departemen - DISEJAJARKAN --}}
                    <div class="row g-3 mb-3 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label fw-semibold mb-2">Pilih Absen</label>
                            <select name="tipe_absen" class="form-select" {{ $sudahAbsenPulang ? 'disabled' : '' }}
                                required>
                                @if (!$sudahAbsenMasuk)
                                    <option value="masuk">Absen Masuk</option>
                                @elseif ($sudahAbsenMasuk && !$sudahAbsenPulang)
                                    <option value="pulang">Absen Pulang</option>
                                @endif
                            </select>

                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-semibold mb-2">Tanggal</label>
                            <input type="text" name="tanggal" class="form-control" value="{{ now()->format('d/m/Y') }}"
                                readonly>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold mb-2">Pilih Shift</label>
                            <select name="jam_kerja_id" class="form-select" {{ $sudahAbsenMasuk ? 'disabled' : '' }}
                                required>
                                <option value="">-- Pilih Shift --</option>
                                @foreach ($jamKerjas as $jam)
                                    <option value="{{ $jam->id }}"
                                        {{ isset($selectedShift) && $selectedShift == $jam->id ? 'selected' : '' }}>
                                        {{ ucfirst($jam->jenis_shift) }} ({{ $jam->jam_masuk }} - {{ $jam->jam_keluar }})
                                    </option>
                                @endforeach
                            </select>
                            @if ($sudahAbsenMasuk)
                                <input type="hidden" name="jam_kerja_id" value="{{ $selectedShift }}">
                            @endif
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-semibold mb-2">Departement</label>
                            <input type="text" class="form-control text-uppercase" value="{{ Auth::user()->role }}"
                                readonly>
                        </div>
                    </div>

                    {{-- Baris 2: No ID, Nama, Waktu --}}
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold mb-2">No ID</label>
                            <input type="text" name="badge_number" class="form-control"
                                value="{{ Auth::user()->badge_number }}" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold mb-2">Nama</label>
                            <input type="text" name="nama" class="form-control" value="{{ Auth::user()->name }}"
                                readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold mb-2">Waktu</label>
                            <input type="time" name="waktu" class="form-control" value="{{ now()->format('H:i') }}"
                                readonly required>
                        </div>
                    </div>

                    {{-- Baris 3: Foto & Lokasi --}}
                    <div class="row g-3 mb-4">
                        {{-- FOTO --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold d-block mb-2">Foto Kehadiran</label>
                            <div class="border rounded mb-3 overflow-hidden" style="height: 280px; background: #f8f9fa;">
                                <video id="camera" autoplay playsinline
                                    style="width:100%; height:100%; object-fit:cover;"></video>
                            </div>
                            <div class="text-center">
                                <button type="button" class="btn btn-secondary px-4" id="ambilFoto">
                                    Ambil Foto
                                </button>
                            </div>
                            <input type="hidden" name="foto" id="fotoInput">
                            <canvas id="canvas" style="display:none;"></canvas>
                        </div>

                        {{-- LOKASI --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold d-block mb-2">Lokasi</label>
                            <div id="lokasi"
                                class="border rounded mb-3 bg-light d-flex align-items-center justify-content-center"
                                style="height:280px;">
                                <span class="text-muted">Belum ada lokasi</span>
                            </div>
                            <div class="text-center">
                                <button type="button" class="btn btn-secondary px-4" id="ambilLokasi">
                                    Ambil Lokasi
                                </button>
                            </div>
                            <input type="hidden" name="lokasi" id="lokasiInput">
                        </div>
                    </div>

                    {{-- Button Submit --}}
                    @if (!$sudahAbsenPulang)
                        <div class="d-flex justify-content-end mt-4 pt-3 border-top">
                            <button type="submit" class="btn btn-primary px-5 py-2 fw-semibold">
                                ✓ Simpan Absensi
                            </button>
                        </div>
                    @else
                        <div class="alert alert-success text-center mt-4">
                            ✅ Anda sudah melakukan absensi masuk dan pulang hari ini.
                        </div>
                    @endif

                </form>
            </div>
        </div>
    </div>

    {{-- JavaScript untuk Kamera & Lokasi --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            console.log("✅ Script loaded");

            // ========== KAMERA ==========
            const video = document.getElementById("camera");
            const canvas = document.getElementById("canvas");
            const fotoInput = document.getElementById("fotoInput");
            const ambilFotoBtn = document.getElementById("ambilFoto");
            let cameraStream = null; // Simpan stream untuk di-stop nanti

            // Aktifkan kamera
            navigator.mediaDevices.getUserMedia({
                    video: true
                })
                .then(stream => {
                    cameraStream = stream; // Simpan stream
                    video.srcObject = stream;
                    console.log("✅ Kamera aktif");
                })
                .catch(error => {
                    console.error("❌ Kamera error:", error);
                    alert("Kamera gagal diakses. Pastikan izin kamera diaktifkan.");
                    ambilFotoBtn.disabled = true;
                });

            // Ambil foto
            ambilFotoBtn.addEventListener("click", () => {
                if (!cameraStream) {
                    alert("Kamera belum siap!");
                    return;
                }

                // Ambil gambar dari video
                const context = canvas.getContext("2d");
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                context.drawImage(video, 0, 0);

                const imageData = canvas.toDataURL("image/png");
                fotoInput.value = imageData;

                console.log("📸 Foto diambil, mulai matikan kamera...");

                // MATIKAN KAMERA - Method 1: Stop semua tracks dari stream
                if (cameraStream) {
                    cameraStream.getTracks().forEach(track => {
                        track.stop();
                        console.log("🔴 Stream track stopped:", track.label);
                    });
                }

                // MATIKAN KAMERA - Method 2: Stop tracks dari video.srcObject
                if (video.srcObject) {
                    const tracks = video.srcObject.getTracks();
                    tracks.forEach(track => {
                        track.stop();
                        console.log("🔴 Video track stopped:", track.label);
                    });
                    video.srcObject = null;
                }

                // MATIKAN KAMERA - Method 3: Pause video element
                video.pause();
                video.src = "";
                video.load();

                // Reset stream variable
                cameraStream = null;

                console.log("✅ Kamera berhasil dimatikan!");

                // Ganti tampilan dengan foto hasil
                const container = video.parentElement;
                container.innerHTML = `
            <img src="${imageData}" alt="Hasil Foto"
                 style="width:100%; height:100%; object-fit:cover;">
        `;

                // Update button
                ambilFotoBtn.innerHTML = "🔄 Ambil Ulang";
                ambilFotoBtn.classList.remove('btn-secondary');
                ambilFotoBtn.classList.add('btn-success');
                ambilFotoBtn.onclick = () => location.reload();

                console.log("✅ Foto diambil & UI updated");
            });

            // ========== LOKASI ==========
            const ambilLokasiBtn = document.getElementById('ambilLokasi');
            const lokasiDiv = document.getElementById('lokasi');
            const lokasiInput = document.getElementById('lokasiInput');

            ambilLokasiBtn.addEventListener('click', function() {
                if (!navigator.geolocation) {
                    alert('Browser tidak mendukung geolocation.');
                    return;
                }

                // Tampilkan loading
                lokasiDiv.innerHTML = `
            <div class="text-center">
                <div class="spinner-border text-primary mb-2" role="status"></div>
                <p class="text-muted mb-0">Mengambil lokasi...</p>
            </div>
        `;
                ambilLokasiBtn.disabled = true;

                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;
                        const coords = `${lat},${lng}`;
                        lokasiInput.value = coords;

                        // Tampilkan peta
                        lokasiDiv.innerHTML = `
                    <iframe
                        width="100%"
                        height="100%"
                        style="border:0;"
                        loading="lazy"
                        allowfullscreen
                        referrerpolicy="no-referrer-when-downgrade"
                        src="https://www.google.com/maps?q=${lat},${lng}&hl=id&z=17&output=embed">
                    </iframe>
                `;

                        // Update button
                        ambilLokasiBtn.disabled = false;
                        ambilLokasiBtn.innerHTML = "✓ Lokasi Berhasil";
                        ambilLokasiBtn.classList.remove('btn-secondary');
                        ambilLokasiBtn.classList.add('btn-success');

                        console.log("✅ Lokasi diambil:", coords);
                    },
                    function(error) {
                        let errorMsg = '';
                        switch (error.code) {
                            case error.PERMISSION_DENIED:
                                errorMsg = 'Izin lokasi ditolak. Aktifkan izin lokasi di browser.';
                                break;
                            case error.POSITION_UNAVAILABLE:
                                errorMsg = 'Informasi lokasi tidak tersedia.';
                                break;
                            case error.TIMEOUT:
                                errorMsg = 'Waktu pengambilan lokasi habis.';
                                break;
                            default:
                                errorMsg = 'Gagal mengambil lokasi.';
                        }

                        alert(errorMsg);
                        lokasiDiv.innerHTML = `<span class="text-muted">Belum ada lokasi</span>`;
                        ambilLokasiBtn.disabled = false;

                        console.error("❌ Lokasi error:", error);
                    }
                );
            });

            // ========== VALIDASI FORM - WAJIB ISI FOTO & LOKASI ==========
            const form = document.querySelector('form');
            const fotoError = document.getElementById('fotoError');
            const lokasiError = document.getElementById('lokasiError');

            form.addEventListener('submit', function(e) {
                let isValid = true;

                // Validasi foto
                if (!fotoInput.value || fotoInput.value.trim() === '') {
                    e.preventDefault();
                    fotoError.style.display = 'block';
                    ambilFotoBtn.classList.add('btn-danger');
                    ambilFotoBtn.classList.remove('btn-secondary');
                    isValid = false;

                    // Scroll ke foto
                    ambilFotoBtn.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                } else {
                    fotoError.style.display = 'none';
                }

                // Validasi lokasi
                if (!lokasiInput.value || lokasiInput.value.trim() === '') {
                    e.preventDefault();
                    lokasiError.style.display = 'block';
                    ambilLokasiBtn.classList.add('btn-danger');
                    ambilLokasiBtn.classList.remove('btn-secondary');
                    isValid = false;

                    // Scroll ke lokasi jika foto sudah ada
                    if (fotoInput.value) {
                        ambilLokasiBtn.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                    }
                } else {
                    lokasiError.style.display = 'none';
                }

                if (!isValid) {
                    // Trigger HTML5 validation
                    form.reportValidity();
                    return false;
                }

                console.log("✅ Form valid - Submitting...");
                return true;
            });

            // Reset error saat button diklik
            ambilFotoBtn.addEventListener('click', function() {
                fotoError.style.display = 'none';
                ambilFotoBtn.classList.remove('btn-danger');
            });

            ambilLokasiBtn.addEventListener('click', function() {
                lokasiError.style.display = 'none';
                ambilLokasiBtn.classList.remove('btn-danger');
            });

            // Matikan kamera saat user meninggalkan halaman
            window.addEventListener('beforeunload', function() {
                console.log("🔴 Halaman akan ditutup, matikan kamera...");
                if (cameraStream) {
                    cameraStream.getTracks().forEach(track => track.stop());
                }
                if (video.srcObject) {
                    video.srcObject.getTracks().forEach(track => track.stop());
                    video.srcObject = null;
                }
            });

            // Matikan kamera setelah form berhasil submit
            form.addEventListener('submit', function() {
                setTimeout(function() {
                    console.log("🔴 Form submitted, matikan kamera...");
                    if (cameraStream) {
                        cameraStream.getTracks().forEach(track => track.stop());
                        cameraStream = null;
                    }
                    if (video.srcObject) {
                        video.srcObject.getTracks().forEach(track => track.stop());
                        video.srcObject = null;
                    }
                    video.pause();
                }, 100);
            });
        });
    </script>

    {{-- Custom CSS --}}
    <style>
        /* Konsistensi spacing */
        .form-label {
            font-size: 14px;
        }

        .form-control,
        .form-select {
            height: 38px;
            font-size: 14px;
        }

        /* Card styling */
        .card {
            border-radius: 12px;
        }

        /* Button hover effects */
        .btn {
            transition: all 0.3s ease;
        }

        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .btn-secondary {
            background-color: #6c757d;
            border: none;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
        }

        .btn-primary {
            background-color: #0d6efd;
            border: none;
        }

        .btn-primary:hover {
            background-color: #0b5ed7;
        }

        .btn-success {
            background-color: #198754;
            border: none;
        }

        .btn-danger {
            background-color: #dc3545;
            border: none;
            animation: shake 0.5s;
        }

        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            25% {
                transform: translateX(-5px);
            }

            75% {
                transform: translateX(5px);
            }
        }

        /* Border styling */
        .border {
            border: 2px solid #dee2e6 !important;
        }

        /* Error message styling */
        .invalid-feedback {
            color: #dc3545;
            font-size: 13px;
            font-weight: 500;
        }

        /* Required asterisk */
        .text-danger {
            color: #dc3545;
        }

        /* Responsive */
        @media (max-width: 768px) {

            .col-md-3,
            .col-md-4,
            .col-md-6 {
                margin-bottom: 10px;
            }

            #camera,
            #lokasi {
                height: 220px !important;
            }
        }
    </style>
@endsection
