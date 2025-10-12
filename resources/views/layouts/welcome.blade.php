<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@yield('title', 'e-Absence, Leave, & Overtime - PT. Vortex Energy Batam')</title>

    <!-- Custom fonts for this template-->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">

    @stack('styles')

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Nunito', sans-serif;
            background: white;
            min-height: 100vh;
            color: white;
            margin: 0;
        }

        /* Header */
        .header {
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            padding: 1.5rem 0;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            width: 100%;
            z-index: 1000;
            transition: padding 0.3s ease, box-shadow 0.3s ease;
        }

        /* Add padding to body to prevent content hiding under fixed header */
        body {
            padding-top: 90px;
        }

        /* Scrolled header effect */
        .header.scrolled {
            padding: 1rem 0;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        .header .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 1.75rem;
            font-weight: 800;
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .logo img {
            height: 40px;
        }

        .logo:hover {
            color: white;
            text-decoration: none;
        }

        .btn-login-header {
            background: white;
            color: #4e73df;
            padding: 0.75rem 2rem;
            font-size: 1rem;
            font-weight: 700;
            border-radius: 0.5rem;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }

        .btn-login-header:hover {
            background: #f8f9fc;
            color: #224abe;
            transform: translateY(-2px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            text-decoration: none;
        }

        /* Main Content */
        .main-content {
            background: white;
            padding: 4rem 0;
            text-align: center;
        }

        .main-title {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1.2;
        }

        .main-subtitle {
            font-size: 1.5rem;
            margin-bottom: 1rem;
            color: #5a5c69;
            font-weight: 600;
        }

        .main-description {
            font-size: 1.1rem;
            color: #858796;
            max-width: 600px;
            margin: 0 auto;
            line-height: 1.6;
        }

        /* Features Section */
        .features-section {
            background: white;
            padding: 3rem 0 4rem 0;
        }

        .feature-card {
            background: white;
            border-radius: 1rem;
            padding: 2.5rem 1.5rem;
            text-align: center;
            transition: all 0.3s ease;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            height: 100%;
            border: 1px solid #e3e6f0;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 0.5rem 2rem rgba(78, 115, 223, 0.25);
            border-color: #4e73df;
        }

        .feature-icon {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            box-shadow: 0 0.5rem 1rem rgba(78, 115, 223, 0.3);
        }

        .feature-icon i {
            font-size: 2.5rem;
            color: white;
        }

        .feature-card h4 {
            color: #5a5c69;
            font-weight: 700;
            margin-bottom: 0.75rem;
            font-size: 1.3rem;
        }

        .feature-card p {
            color: #858796;
            font-size: 1rem;
            margin-bottom: 0;
            line-height: 1.6;
        }

        /* Footer */
        .footer {
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            padding: 2rem 0;
            margin-top: 0;
        }

        .footer-text {
            text-align: center;
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.95rem;
            margin: 0;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .header .container {
                flex-direction: column;
                gap: 1rem;
            }

            .main-title {
                font-size: 2.5rem;
            }

            .main-subtitle {
                font-size: 1.2rem;
            }

            .main-description {
                font-size: 1rem;
                padding: 0 1rem;
            }

            .btn-login-header {
                padding: 0.625rem 1.5rem;
                font-size: 0.95rem;
            }

            .feature-icon {
                width: 80px;
                height: 80px;
            }

            .feature-icon i {
                font-size: 2rem;
            }
        }
    </style>
</head>

<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <a href="{{ url('/') }}" class="logo">
                <img src="{{ asset('img/logo.png') }}" alt="Vortex Logo">
            </a>
            <a href="{{ route('login') }}" class="btn-login-header">
                <i class="fas fa-sign-in-alt mr-2"></i>Login
            </a>
        </div>
    </header>

    <!-- Main Content -->
    @yield('content')

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p class="footer-text">
                © {{ date('Y') }} PT. Vortex Energy Batam. All Rights Reserved.
            </p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>

</html>