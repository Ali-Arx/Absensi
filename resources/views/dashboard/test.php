<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Dashboard HR - PT. Vortex Energy Batam</title>

    <!-- Custom fonts for this template-->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/startbootstrap-sb-admin-2/4.1.4/css/sb-admin-2.min.css"
        rel="stylesheet">

    <style>
        body {
            font-family: 'Nunito', -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', sans-serif;
        }

        .info-card {
            background: (135deg, #ffffff 100%,  );
            border-radius: 15px;
            padding: 25px;
            color: rgb(79, 79, 79);
            height: 180px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .info-card h5 {
            font-size: 18px;
            margin-bottom: 15px;
            font-weight: 700;
        }

        .info-card p {
            margin: 5px 0;
            font-size: 15px;
            font-weight: 500;
        }

        .table-responsive {
            margin-top: 20px;
        }

        .table thead th {
            background-color: #f8f9fc;
            color: #5a5c69;
            font-weight: 700;
            border-bottom: 2px solid #e3e6f0;
            padding: 15px;
            font-size: 13px;
            text-transform: uppercase;
        }

        .table tbody tr:nth-child(odd) {
            background-color: #f8f9fc;
        }

        .table tbody tr:nth-child(even) {
            background-color: white;
        }

        .table tbody td {
            padding: 15px;
            font-size: 14px;
            color: #5a5c69;
        }

        .card-stats {
            border-left: 4px solid;
            border-radius: 8px;
        }

        .card-stats .card-body {
            display: flex;
            align-items: center;
            min-height: 100px;
        }

        .card-stats.border-left-primary {
            border-left-color: #4e73df;
        }

        .card-stats.border-left-success {
            border-left-color: #1cc88a;
        }

        .card-stats.border-left-info {
            border-left-color: #36b9cc;
        }

        .card-stats.border-left-warning {
            border-left-color: #f6c23e;
        }

        .stats-icon {
            font-size: 2rem;
            color: #dddfeb;
        }

        .progress-sm {
            height: 0.5rem;
        }

        /* Custom Sidebar Styling */
        .sidebar {
            background: linear-gradient(180deg, #4e73df 0%, #224abe 100%);
        }

        .sidebar .nav-item .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 1rem;
        }

        .sidebar .nav-item .nav-link:hover {
            color: #fff;
        }

        .sidebar .nav-item.active .nav-link {
            color: #fff;
            font-weight: 700;
        }

        .sidebar-brand {
            height: 4.375rem;
            padding: 1.5rem 1rem;
        }

        .sidebar-brand-icon i {
            font-size: 2rem;
            color: white;
        }

        .sidebar-divider {
            border-top: 1px solid rgba(255, 255, 255, 0.15);
        }

        .sidebar-heading {
            color: rgba(255, 255, 255, 0.4);
            font-size: 0.65rem;
            font-weight: 800;
            padding: 0 1rem;
            text-transform: uppercase;
            letter-spacing: 0.05rem;
        }

        /* Topbar */
        .topbar {
            height: 4.375rem;
            background-color: #fff;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }

        .company-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #5a5c69;
            margin-bottom: 0;
        }

        /* Make all stat cards same height */
        .stat-card-wrapper {
            display: flex;
            margin-bottom: 1.5rem;
        }

        .stat-card-wrapper .card {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .stat-card-wrapper .card-body {
            flex: 1;
        }
    </style>
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="#">
                <img src="{{ asset('img/logo.png') }}" 
             alt="Logo" 
             style="width: 100px; height: auto; object-fit: contain; display: block;">
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="#">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                INTERFACE
            </div>

            <!-- Nav Item - Absensi Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseAbsensi"
                    aria-expanded="true" aria-controls="collapseAbsensi">
                    <i class="fas fa-fw fa-calendar-check"></i>
                    <span>Absensi</span>
                </a>
                <div id="collapseAbsensi" class="collapse" aria-labelledby="headingAbsensi"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" href="#">Absensi</a>
                        <a class="collapse-item" href="#">Riwayat Absensi</a>
                        <a class="collapse-item" href="#">Data Absensi</a>
                    </div>
                </div>
            </li>

            <!-- Nav Item - Cuti Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseCuti"
                    aria-expanded="true" aria-controls="collapseCuti">
                    <i class="fas fa-fw fa-calendar-alt"></i>
                    <span>Cuti</span>
                </a>
                <div id="collapseCuti" class="collapse" aria-labelledby="headingCuti" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" href="#">Pengajuan Cuti</a>
                        <a class="collapse-item" href="#">Approval Cuti</a>
                        <a class="collapse-item" href="#">Data Cuti</a>
                    </div>
                </div>
            </li>

            <!-- Nav Item - Lembur Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseLembur"
                    aria-expanded="true" aria-controls="collapseLembur">
                    <i class="fas fa-fw fa-clock"></i>
                    <span>Lembur</span>
                </a>
                <div id="collapseLembur" class="collapse" aria-labelledby="headingLembur"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" href="#">Pengajuan Lembur</a>
                        <a class="collapse-item" href="#">Approval Lembur</a>
                        <a class="collapse-item" href="#">Data Lembur</a>
                    </div>
                </div>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                MANAGEMENT
            </div>

            <!-- Nav Item - Pengguna -->
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="fas fa-fw fa-users"></i>
                    <span>Pengguna</span>
                </a>
            </li>

            <!-- Nav Item - Pengaturan -->
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="fas fa-fw fa-cog"></i>
                    <span>Pengaturan</span>
                </a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Company Title -->
                    <h1 class="company-title">PT.Vortex Energy Batam</h1>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                                    <strong>Nama</strong><br>
                                    <small>Jabatan</small>
                                </span>
                                <div class="rounded-circle bg-gray-800 text-white d-flex align-items-center justify-content-center"
                                    style="width: 40px; height: 40px;">
                                    <i class="fas fa-user"></i>
                                </div>
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Settings
                                </a>
                                <div class="dropdown-divider"></div>

                                <!-- Tombol Logout -->
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                        Logout
                                    </button>
                                </form>
                            </div>

                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Dashboard HR</h1>
                    </div>

                    <!-- Content Row - Statistics Cards -->
                    <div class="row">

                        <!-- Kehadiran Card -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card card-stats border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Kehadiran</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">240</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-calendar stats-icon"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Total Karyawan Card -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card card-stats border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Total Karyawan</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">250</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-users stats-icon"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tidak Hadir Card -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card card-stats border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                                Tidak Hadir</div>
                                            <div class="row no-gutters align-items-center">
                                                <div class="col-auto">
                                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">10</div>
                                                </div>
                                                <div class="col">
                                                    <div class="progress progress-sm mr-2">
                                                        <div class="progress-bar bg-info" role="progressbar"
                                                            style="width: 50%" aria-valuenow="50" aria-valuemin="0"
                                                            aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-clipboard-list stats-icon"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        

                    </div>

                    <!-- Content Row - Info Cards -->
                    <div class="row">

                        <!-- Absen Datang Card -->
                        <div class="col-lg-4 mb-4">
                            <div class="info-card align-items-center">
                                <h5 te>Absen Datang</h5>
                                <p>Normal : 08:00:00</p>
                                <p>Malam : 15:30:00</p>
                            </div>
                        </div>

                        <!-- Hari Card -->
                        <div class="col-lg-4 mb-4">
                            <div class="info-card align-items-center">
                                <h5>Hari</h5>
                                <p>Senin</p>
                                <p id="currentDate">Tanggal 08/10/25</p>
                            </div>
                        </div>

                        <!-- Absen Pulang Card -->
                        <div class="col-lg-4 mb-4">
                            <div class="info-card align-items-center">
                                <h5>Absen Pulang</h5>
                                <p>Normal : 17:00:00</p>
                                <p>Malam : 00:00:00</p>
                            </div>
                        </div>

                    </div>

                    <!-- Data Table -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Data Kehadiran Karyawan</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Departmen</th>
                                            <th>Nama</th>
                                            <th>No ID</th>
                                            <th>Tanggal</th>
                                            <th>Waktu<br>In | Out</th>
                                            <th>Lokasi</th>
                                            <th>Kode<br>Verifikasi</th>
                                            <th>Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Staff</td>
                                            <td>-</td>
                                            <td>-</td>
                                            <td>-</td>
                                            <td>-</td>
                                            <td>Kantor</td>
                                            <td>Fingerprint</td>
                                            <td>-</td>
                                        </tr>
                                        <tr>
                                            <td>Engineering</td>
                                            <td>-</td>
                                            <td>-</td>
                                            <td>-</td>
                                            <td>-</td>
                                            <td>-</td>
                                            <td>-</td>
                                            <td>Sakit</td>
                                        </tr>
                                        <tr>
                                            <td>Production</td>
                                            <td>-</td>
                                            <td>-</td>
                                            <td>-</td>
                                            <td>-</td>
                                            <td>-</td>
                                            <td>-</td>
                                            <td>Cuti</td>
                                        </tr>
                                        <tr>
                                            <td>Internship</td>
                                            <td>-</td>
                                            <td>-</td>
                                            <td>-</td>
                                            <td>-</td>
                                            <td>Kantor</td>
                                            <td>Fingerprint</td>
                                            <td>Terlambat</td>
                                        </tr>
                                        <tr>
                                            <td>Sales</td>
                                            <td>-</td>
                                            <td>-</td>
                                            <td>-</td>
                                            <td>-</td>
                                            <td>PT EPSON BATAM</td>
                                            <td>Online</td>
                                            <td>Visit</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; PT. Vortex Energy Batam 2025</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Bootstrap core JavaScript-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.0/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/startbootstrap-sb-admin-2/4.1.4/js/sb-admin-2.min.js"></script>

    <script>
        // Logout functionality dengan konfirmasi
        document.getElementById('logoutBtn').addEventListener('click', function(e) {
            e.preventDefault();
            if (confirm('Apakah Anda yakin ingin logout?')) {
                alert('Logout berhasil!');
                // Redirect ke halaman login atau landing page
                window.location.href = '/';
            }
        });

        // Real-time clock update
        function updateDateTime() {
            const now = new Date();
            const day = now.getDate().toString().padStart(2, '0');
            const month = (now.getMonth() + 1).toString().padStart(2, '0');
            const year = now.getFullYear().toString().slice(-2);

            const dateElement = document.getElementById('currentDate');
            if (dateElement) {
                dateElement.textContent = `Tanggal ${day}/${month}/${year}`;
            }
        }

        // Update every minute
        setInterval(updateDateTime, 60000);
        updateDateTime();

        // Table hover effects
        $(document).ready(function() {
            $('#dataTable tbody tr').hover(
                function() {
                    $(this).css('background-color', '#e3f2fd');
                },
                function() {
                    const index = $(this).index();
                    $(this).css('background-color', index % 2 === 0 ? '#f8f9fc' : 'white');
                }
            );
        });

        console.log('Dashboard HR PT. Vortex Energy Batam loaded successfully!');
    </script>
</body>

</html>


ini file hr.blade.php

