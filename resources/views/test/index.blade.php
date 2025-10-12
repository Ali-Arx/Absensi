<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PT. Vortex Energy Batam - e-Absence System</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --sidebar-width: 220px;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }
        
        /* Login & Landing Page Styles */
        .landing-page {
            min-height: 100vh;
            background: linear-gradient(135deg, #e0e0e0 0%, #f5f5f5 100%);
        }
        
        .landing-header {
            padding: 20px 50px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: white;
        }
        
        .landing-logo {
            font-size: 28px;
            font-weight: bold;
        }
        
        .login-dropdown {
            position: relative;
        }
        
        .login-btn {
            background-color: #666;
            color: white;
            padding: 10px 30px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .dropdown-menu-custom {
            position: absolute;
            right: 0;
            top: 50px;
            background: #666;
            border-radius: 8px;
            padding: 10px 0;
            min-width: 150px;
            display: none;
            z-index: 1000;
        }
        
        .dropdown-menu-custom.show {
            display: block;
        }
        
        .dropdown-menu-custom a {
            color: white;
            padding: 10px 20px;
            display: block;
            text-decoration: none;
        }
        
        .dropdown-menu-custom a:hover {
            background-color: #555;
        }
        
        .landing-content {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: calc(100vh - 100px);
            text-align: center;
            color: white;
            padding: 50px;
        }
        
        .landing-text h1 {
            font-size: 48px;
            font-weight: bold;
            margin-bottom: 30px;
        }
        
        .landing-text p {
            font-size: 20px;
            line-height: 1.6;
        }
        
        /* Login Form Styles */
        .login-page {
            min-height: 100vh;
            display: flex;
            background: white;
        }
        
        .login-left {
            flex: 1;
            background-color: #d0d0d0;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .login-right {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 50px;
        }
        
        .login-form-container {
            width: 100%;
            max-width: 450px;
        }
        
        .login-form-container h2 {
            font-size: 36px;
            font-weight: bold;
            margin-bottom: 40px;
        }
        
        .form-group-login {
            margin-bottom: 25px;
        }
        
        .form-group-login input {
            width: 100%;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
        }
        
        .input-with-icon {
            position: relative;
        }
        
        .input-with-icon i {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
            cursor: pointer;
        }
        
        .btn-login {
            width: 100%;
            padding: 15px;
            background-color: #666;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 18px;
            font-weight: 500;
            cursor: pointer;
        }
        
        .btn-login:hover {
            background-color: #555;
        }
        
        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background-color: #fff;
            padding: 20px 15px;
            overflow-y: auto;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
            z-index: 1000;
        }
        
        .sidebar .logo {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 30px;
            padding: 0 10px;
        }
        
        .sidebar .nav-item {
            margin-bottom: 8px;
        }
        
        .sidebar .nav-link {
            color: #333;
            padding: 12px 15px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: #f8f9fa;
            border: 1px solid #e0e0e0;
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .sidebar .nav-link:hover {
            background-color: #e9ecef;
        }
        
        .sidebar .nav-link.active {
            background-color: #e0e0e0;
        }
        
        .sidebar .submenu {
            list-style: none;
            padding-left: 20px;
            margin-top: 5px;
            display: none;
        }
        
        .sidebar .submenu.show {
            display: block;
        }
        
        .sidebar .submenu li {
            margin-bottom: 5px;
        }
        
        .sidebar .submenu a {
            color: #666;
            text-decoration: none;
            font-size: 14px;
            display: block;
            padding: 5px 10px;
        }
        
        .sidebar .submenu a:hover {
            color: #333;
        }
        
        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
        }
        
        .header {
            background-color: #fff;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .header h1 {
            font-size: 24px;
            font-weight: bold;
            margin: 0;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .user-profile {
            display: flex;
            align-items: center;
            gap: 10px;
            background-color: #e0e0e0;
            padding: 8px 15px;
            border-radius: 25px;
        }
        
        .user-avatar {
            width: 35px;
            height: 35px;
            background-color: #333;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
        }
        
        .logout-btn {
            background-color: #666;
            color: #fff;
            padding: 8px 20px;
            border-radius: 8px;
            border: none;
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }
        
        .content-area {
            padding: 30px;
        }
        
        /* Dashboard Cards */
        .dashboard-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .dashboard-card {
            background: linear-gradient(135deg, #c0c0c0 0%, #d0d0d0 100%);
            padding: 30px;
            border-radius: 15px;
            color: white;
            text-align: center;
        }
        
        .dashboard-card h3 {
            font-size: 20px;
            margin-bottom: 10px;
        }
        
        .dashboard-card .number {
            font-size: 48px;
            font-weight: bold;
        }
        
        .dashboard-info {
            background: linear-gradient(135deg, #b0b0b0 0%, #c0c0c0 100%);
            padding: 20px 30px;
            border-radius: 15px;
            color: white;
        }
        
        .dashboard-info p {
            margin: 8px 0;
            font-size: 16px;
        }
        
        /* Employee Dashboard */
        .greeting {
            font-size: 28px;
            margin-bottom: 20px;
        }
        
        .status-info {
            font-size: 18px;
            margin-bottom: 30px;
        }
        
        .status-info i {
            margin-right: 10px;
        }
        
        /* Table Styles */
        .table-container {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .table {
            margin-bottom: 0;
        }
        
        .table thead {
            background-color: #f8f9fa;
        }
        
        .table tbody tr:nth-child(odd) {
            background-color: #f8f9fa;
        }
        
        .table tbody tr:nth-child(even) {
            background-color: white;
        }
        
        /* Utility Classes */
        .hidden {
            display: none;
        }
        
        .text-center {
            text-align: center;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .dashboard-cards {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Landing Page -->
    <div id="landingPage" class="landing-page">
        <div class="landing-header">
            <div class="landing-logo">LOGO</div>
            <div class="login-dropdown">
                <a href="{{ route('login') }}" class="login-btn" id="loginDropdownBtn">
                    Login 
                </a>
        
            </div>
        </div>
        <div class="landing-content">
            <div class="landing-text">
                <h1>e-Absence, Leave, & Overtime</h1>
                <p>adalah sistem pencatatan absensi,<br>
                pengajuan cuti dan lembur<br>
                berbasis website</p>
            </div>
        </div>
    </div>

    <!-- Login Page -->
    <div id="loginPage" class="login-page hidden">
        <div class="login-left">
            <div style="width: 300px; height: 300px; background: #aaa; border-radius: 10px;"></div>
        </div>
        <div class="login-right">
            <div class="login-form-container">
                <h2>Selamat Datang</h2>
                <form id="loginForm">
                    <div class="form-group-login">
                        <input type="email" placeholder="Masukan Email" required>
                    </div>
                    <div class="form-group-login">
                        <div class="input-with-icon">
                            <input type="password" id="passwordInput" placeholder="Masukan Kata Sandi" required>
                            <i class="fas fa-eye" id="togglePasswordBtn"></i>
                        </div>
                    </div>
                    <button type="submit" class="btn-login">Masuk</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Dashboard Admin/HR -->
    <div id="dashboardAdmin" class="hidden">
        <div class="sidebar">
            <div class="logo">LOGO</div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link active" href="#" data-page="dashboardAdmin">
                        <span><i class="fas fa-layer-group me-2"></i> Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" data-submenu="absensiMenu">
                        <span><i class="fas fa-calendar-check me-2"></i> Absensi</span>
                        <i class="fas fa-chevron-down"></i>
                    </a>
                    <ul class="submenu" id="absensiMenu">
                        <li><a href="#" data-page="absensi">• Absensi</a></li>
                        <li><a href="#" data-page="riwayatAbsensi">• Riwayat Absensi</a></li>
                        <li><a href="#" data-page="dataAbsensi">• Data Absensi</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" data-submenu="cutiMenu">
                        <span><i class="fas fa-calendar-alt me-2"></i> Cuti</span>
                        <i class="fas fa-chevron-down"></i>
                    </a>
                    <ul class="submenu" id="cutiMenu">
                        <li><a href="#" data-page="pengajuanCuti">• Pengajuan Cuti</a></li>
                        <li><a href="#" data-page="approvalCuti">• Approval Cuti</a></li>
                        <li><a href="#" data-page="dataCuti">• Data Cuti</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" data-submenu="lemburMenu">
                        <span><i class="fas fa-clock me-2"></i> Lembur</span>
                        <i class="fas fa-chevron-down"></i>
                    </a>
                    <ul class="submenu" id="lemburMenu">
                        <li><a href="#" data-page="pengajuanLembur">• Pengajuan Lembur</a></li>
                        <li><a href="#" data-page="approvalLembur">• Approval Lembur</a></li>
                        <li><a href="#" data-page="dataLembur">• Data Lembur</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" data-page="pengguna">
                        <span><i class="fas fa-users me-2"></i> Pengguna</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" data-page="pengaturan">
                        <span><i class="fas fa-cog me-2"></i> Pengaturan</span>
                    </a>
                </li>
            </ul>
        </div>

        <div class="main-content">
            <div class="header">
                <h1>PT.Vortex Energy Batam</h1>
                <div class="user-info">
                    <div class="user-profile">
                        <div class="user-avatar"><i class="fas fa-user"></i></div>
                        <div>
                            <div style="font-weight: bold;">Nama</div>
                            <div style="font-size: 12px; color: #666;">Jabatan</div>
                        </div>
                    </div>
                    <button class="logout-btn" id="logoutBtnAdmin">
                        <i class="fas fa-sign-out-alt"></i> Log Out
                    </button>
                </div>
            </div>

            <div class="content-area">
                <h2 class="mb-4">Dashboard</h2>
                <div class="dashboard-cards">
                    <div class="dashboard-card">
                        <h3>Kehadiran</h3>
                        <div class="number">240</div>
                    </div>
                    <div class="dashboard-card">
                        <h3>Total Karyawan</h3>
                        <div class="number">250</div>
                    </div>
                    <div class="dashboard-card">
                        <h3>Tidak Hadir</h3>
                        <div class="number">10</div>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="dashboard-info">
                            <h5>Absen Datang</h5>
                            <p>Pagi : 07:00:00</p>
                            <p>Normal : 08:00:00</p>
                            <p>malam : 15:30:00</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="dashboard-info">
                            <h5>Hari</h5>
                            <p>Senin</p>
                            <p>Tanggal 20/06/25</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="dashboard-info">
                            <h5>Absen Pulang</h5>
                            <p>Pagi : 16:00:00</p>
                            <p>Normal : 17:00:00</p>
                            <p>Malam : 00:00:00</p>
                        </div>
                    </div>
                </div>

                <div class="table-container">
                    <table class="table">
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
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>Kantor</td>
                                <td>Fingerprint</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Engineering</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>-</td>
                                <td>-</td>
                                <td>Sakit</td>
                            </tr>
                            <tr>
                                <td>Production</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>-</td>
                                <td>-</td>
                                <td>Cuti</td>
                            </tr>
                            <tr>
                                <td>Internship</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>Kantor</td>
                                <td>Fingerprint</td>
                                <td>Terlambat</td>
                            </tr>
                            <tr>
                                <td>Sales</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
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

    <!-- Dashboard Karyawan -->
    <div id="dashboardEmployee" class="hidden">
         <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo">LOGO</div>
        
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <span><i class="fas fa-layer-group me-2"></i> Dashboard</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link active" href="#" data-bs-toggle="collapse" data-bs-target="#absensiMenu">
                    <span><i class="fas fa-calendar-check me-2"></i> Absensi</span>
                    <i class="fas fa-chevron-down"></i>
                </a>
                <ul class="submenu collapse show" id="absensiMenu">
                    <li><a href="#">• Absensi</a></li>
                    <li><a href="#">• Riwayat Absensi</a></li>
                    <li><a href="#">• Data Absensi</a></li>
                </ul>
            </li>
            
            <li class="nav-item">
                <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#cutiMenu">
                    <span><i class="fas fa-calendar-alt me-2"></i> Cuti</span>
                    <i class="fas fa-chevron-down"></i>
                </a>
                <ul class="submenu collapse" id="cutiMenu">
                    <li><a href="#">• Pengajuan Cuti</a></li>
                    <li><a href="#">• Approval Cuti</a></li>
                    <li><a href="#">• Data Cuti</a></li>
                </ul>
            </li>
            
            <li class="nav-item">
                <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#lemburMenu">
                    <span><i class="fas fa-clock me-2"></i> Lembur</span>
                    <i class="fas fa-chevron-down"></i>
                </a>
                <ul class="submenu collapse" id="lemburMenu">
                    <li><a href="#">• Pengajuan Lembur</a></li>
                    <li><a href="#">• Approval Lembur</a></li>
                    <li><a href="#">• Data Lembur</a></li>
                </ul>
            </li>
            
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <span><i class="fas fa-users me-2"></i> Pengguna</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <span><i class="fas fa-cog me-2"></i> Pengaturan</span>
                </a>
            </li>
        </ul>
    </div>

        <div class="main-content">
            <div class="header">
                <h1>PT.Vortex Energy Batam</h1>
                <div class="user-info">
                    <div class="user-profile">
                        <div class="user-avatar"><i class="fas fa-user"></i></div>
                        <div>
                            <div style="font-weight: bold;">Nama</div>
                            <div style="font-size: 12px; color: #666;">Jabatan</div>
                        </div>
                    </div>
                    <button class="logout-btn" id="logoutBtnEmployee">
                        <i class="fas fa-sign-out-alt"></i> Log Out
                    </button>
                </div>
            </div>

            <div class="content-area">
                <h2 class="mb-4">Dashboard</h2>
                <div class="greeting">
                    👋 Selamat Datang, Nama
                </div>
                <div class="status-info">
                    <i class="far fa-calendar"></i> Hari ini: Senin, 25 Juni 2025<br>
                    <i class="far fa-clock"></i> Status Kehadiran: ✅ Sudah Absen
                </div>

                <div class="dashboard-cards">
                    <div class="dashboard-info">
                        <h5>Absen Datang</h5>
                        <p>Pagi : 07:00:00</p>
                        <p>Normal : 08:00:00</p>
                        <p>malam : 15:30:00</p>
                    </div>
                    <div class="dashboard-info">
                        <h5>Absen Pulang</h5>
                        <p>Pagi : 16:00:00</p>
                        <p>Normal : 17:00:00</p>
                        <p>Malam : 00:00:00</p>
                    </div>
                    <div class="dashboard-card">
                        <h3>Total Cuti</h3>
                        <div class="number">3</div>
                    </div>
                    <div class="dashboard-card">
                        <h3>Total OT</h3>
                        <div class="number">10</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    <script>
        // State
        let currentUserType = '';

        // DOM Elements
        const landingPage = document.getElementById('landingPage');
        const loginPage = document.getElementById('loginPage');
        const dashboardAdmin = document.getElementById('dashboardAdmin');
        const dashboardEmployee = document.getElementById('dashboardEmployee');
        const loginDropdownBtn = document.getElementById('loginDropdownBtn');
        const loginDropdown = document.getElementById('loginDropdown');
        const loginForm = document.getElementById('loginForm');
        const togglePasswordBtn = document.getElementById('togglePasswordBtn');
        const passwordInput = document.getElementById('passwordInput');
        const logoutBtnAdmin = document.getElementById('logoutBtnAdmin');
        const logoutBtnEmployee = document.getElementById('logoutBtnEmployee');

        // Toggle Login Dropdown
        loginDropdownBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            loginDropdown.classList.toggle('show');
        });

        // Login Role Selection
        loginDropdown.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                currentUserType = this.getAttribute('data-role');
                landingPage.classList.add('hidden');
                loginPage.classList.remove('hidden');
                loginDropdown.classList.remove('show');
            });
        });

        // Toggle Password Visibility
        togglePasswordBtn.addEventListener('click', function() {
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                this.classList.remove('fa-eye');
                this.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                this.classList.remove('fa-eye-slash');
                this.classList.add('fa-eye');
            }
        });

        // Login Form Submit
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();
            loginPage.classList.add('hidden');
            
            if (currentUserType === 'karyawan') {
                dashboardEmployee.classList.remove('hidden');
            } else {
                dashboardAdmin.classList.remove('hidden');
            }
        });

        // Logout Functions
        function handleLogout() {
            dashboardAdmin.classList.add('hidden');
            dashboardEmployee.classList.add('hidden');
            loginPage.classList.add('hidden');
            landingPage.classList.remove('hidden');
            currentUserType = '';
        }

        logoutBtnAdmin.addEventListener('click', handleLogout);
        logoutBtnEmployee.addEventListener('click', handleLogout);

        // Submenu Toggle
        document.querySelectorAll('[data-submenu]').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const submenuId = this.getAttribute('data-submenu');
                const submenu = document.getElementById(submenuId);
                submenu.classList.toggle('show');
            });
        });

        // Page Navigation
        document.querySelectorAll('[data-page]').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const pageName = this.getAttribute('data-page');
                console.log('Navigasi ke halaman:', pageName);
                alert('Navigasi ke halaman: ' + pageName);
            });
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            if (!loginDropdownBtn.contains(event.target) && !loginDropdown.contains(event.target)) {
                loginDropdown.classList.remove('show');
            }
        });

        // Form validation helper
        function validateEmail(email) {
            const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(email);
        }

        function validatePassword(password) {
            return password.length >= 6;
        }

        // Enhanced login with validation
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const emailInput = this.querySelector('input[type="email"]');
            const passwordInput = this.querySelector('input[type="password"]');
            
            const email = emailInput.value.trim();
            const password = passwordInput.value;
            
            // Validation
            if (!validateEmail(email)) {
                alert('Format email tidak valid!');
                emailInput.focus();
                return;
            }
            
            if (!validatePassword(password)) {
                alert('Password minimal 6 karakter!');
                passwordInput.focus();
                return;
            }
            
            // Simulate login process
            console.log('Login sebagai:', currentUserType);
            console.log('Email:', email);
            
            loginPage.classList.add('hidden');
            
            if (currentUserType === 'karyawan') {
                dashboardEmployee.classList.remove('hidden');
            } else {
                dashboardAdmin.classList.remove('hidden');
            }
            
            // Reset form
            this.reset();
        });

        // Active menu highlighting
        function setActiveMenu(menuElement) {
            // Remove active class from all nav-links
            document.querySelectorAll('.nav-link').forEach(link => {
                link.classList.remove('active');
            });
            
            // Add active class to clicked menu
            if (menuElement.classList.contains('nav-link')) {
                menuElement.classList.add('active');
            } else {
                const parentNavLink = menuElement.closest('.nav-item').querySelector('.nav-link');
                if (parentNavLink) {
                    parentNavLink.classList.add('active');
                }
            }
        }

        // Enhanced navigation with active state
        document.querySelectorAll('[data-page]').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const pageName = this.getAttribute('data-page');
                setActiveMenu(this);
                console.log('Navigasi ke halaman:', pageName);
                
                // You can replace this alert with actual page loading logic
                alert('Navigasi ke halaman: ' + pageName);
            });
        });

        // Enhanced submenu toggle with animation
        document.querySelectorAll('[data-submenu]').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const submenuId = this.getAttribute('data-submenu');
                const submenu = document.getElementById(submenuId);
                const icon = this.querySelector('.fa-chevron-down');
                
                // Toggle submenu
                submenu.classList.toggle('show');
                
                // Rotate icon
                if (submenu.classList.contains('show')) {
                    icon.style.transform = 'rotate(180deg)';
                } else {
                    icon.style.transform = 'rotate(0deg)';
                }
            });
        });

        // Real-time clock for dashboard
        function updateClock() {
            const now = new Date();
            const options = { 
                weekday: 'long', 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            };
            const dateTimeString = now.toLocaleDateString('id-ID', options);
            
            // Update if element exists
            const statusInfo = document.querySelector('.status-info');
            if (statusInfo && !dashboardEmployee.classList.contains('hidden')) {
                const lines = statusInfo.innerHTML.split('<br>');
                lines[0] = `<i class="far fa-calendar"></i> ${dateTimeString}`;
                statusInfo.innerHTML = lines.join('<br>');
            }
        }

        // Update clock every second
        setInterval(updateClock, 1000);

        // Escape key to close modals/dropdowns
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                loginDropdown.classList.remove('show');
            }
        });

        // Prevent form submission on Enter in search fields
        document.querySelectorAll('input[type="search"]').forEach(input => {
            input.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    console.log('Searching for:', this.value);
                }
            });
        });

        // Table row hover effect
        document.querySelectorAll('.table tbody tr').forEach(row => {
            row.addEventListener('mouseenter', function() {
                this.style.backgroundColor = '#e3f2fd';
            });
            
            row.addEventListener('mouseleave', function() {
                // Restore original background
                const index = Array.from(this.parentNode.children).indexOf(this);
                this.style.backgroundColor = index % 2 === 0 ? '#f8f9fa' : 'white';
            });
        });

        // Smooth scroll to top
        function scrollToTop() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        // Add scroll to top button (optional)
        let scrollTopBtn = document.createElement('button');
        scrollTopBtn.innerHTML = '<i class="fas fa-arrow-up"></i>';
        scrollTopBtn.style.cssText = `
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: #666;
            color: white;
            border: none;
            cursor: pointer;
            display: none;
            z-index: 9999;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        `;
        scrollTopBtn.addEventListener('click', scrollToTop);
        document.body.appendChild(scrollTopBtn);

        // Show/hide scroll to top button
        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 300) {
                scrollTopBtn.style.display = 'block';
            } else {
                scrollTopBtn.style.display = 'none';
            }
        });

        // Local storage for remember last page
        function saveLastPage(pageName) {
            const pageData = {
                page: pageName,
                timestamp: new Date().toISOString()
            };
            console.log('Saving page:', pageData);
            // Note: localStorage is not used as per requirements
        }

        // Print functionality
        function printPage() {
            window.print();
        }

        // Export to CSV functionality (basic)
        function exportToCSV(tableId, filename = 'export.csv') {
            const table = document.querySelector(`#${tableId} table`) || document.querySelector('.table');
            if (!table) {
                alert('Tabel tidak ditemukan!');
                return;
            }
            
            let csv = [];
            const rows = table.querySelectorAll('tr');
            
            rows.forEach(row => {
                const cols = row.querySelectorAll('td, th');
                const csvRow = Array.from(cols).map(col => {
                    return '"' + col.textContent.trim().replace(/"/g, '""') + '"';
                });
                csv.push(csvRow.join(','));
            });
            
            const csvContent = csv.join('\n');
            const blob = new Blob([csvContent], { type: 'text/csv' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = filename;
            a.click();
            window.URL.revokeObjectURL(url);
        }

        // Filter table functionality
        function filterTable(searchTerm, tableElement) {
            const table = tableElement || document.querySelector('.table');
            if (!table) return;
            
            const rows = table.querySelectorAll('tbody tr');
            searchTerm = searchTerm.toLowerCase();
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        // Sort table functionality
        function sortTable(columnIndex, tableElement) {
            const table = tableElement || document.querySelector('.table');
            if (!table) return;
            
            const tbody = table.querySelector('tbody');
            const rows = Array.from(tbody.querySelectorAll('tr'));
            
            rows.sort((a, b) => {
                const aText = a.cells[columnIndex].textContent.trim();
                const bText = b.cells[columnIndex].textContent.trim();
                return aText.localeCompare(bText);
            });
            
            rows.forEach(row => tbody.appendChild(row));
        }

        // Notification system
        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                padding: 15px 20px;
                background-color: ${type === 'success' ? '#4caf50' : type === 'error' ? '#f44336' : '#2196F3'};
                color: white;
                border-radius: 8px;
                z-index: 10000;
                box-shadow: 0 4px 8px rgba(0,0,0,0.2);
                animation: slideIn 0.3s ease;
            `;
            notification.textContent = message;
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.style.animation = 'slideOut 0.3s ease';
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }

        // Add CSS animations
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideIn {
                from {
                    transform: translateX(400px);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
            
            @keyframes slideOut {
                from {
                    transform: translateX(0);
                    opacity: 1;
                }
                to {
                    transform: translateX(400px);
                    opacity: 0;
                }
            }
            
            .fa-chevron-down {
                transition: transform 0.3s ease;
            }
        `;
        document.head.appendChild(style);

        // Initialize
        console.log('Sistem PT. Vortex Energy Batam siap digunakan');
        console.log('Versi: 1.0.0');
        console.log('Fitur tersedia:');
        console.log('- Login multi-role');
        console.log('- Dashboard Admin & Karyawan');
        console.log('- Navigasi dengan submenu');
        console.log('- Real-time clock');
        console.log('- Notifikasi');
        console.log('- Export CSV');
        console.log('- Filter & Sort table');
        console.log('- Scroll to top');

        // Example: Show notification on login
        const originalLoginSubmit = loginForm.onsubmit;
        loginForm.addEventListener('submit', function(e) {
            setTimeout(() => {
                showNotification('Login berhasil! Selamat datang.', 'success');
            }, 500);
        });

    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>