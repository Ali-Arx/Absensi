<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Login</title>

    <!-- Custom fonts for this template-->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Nunito', sans-serif;
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-container {
            width: 100%;
            max-width: 480px;
        }

        .login-card {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 0.5rem 2rem rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }

        .login-header img {
            width: 120px;
            height: auto;
            margin-bottom: 1.5rem;
        }

        .login-header {
            display: flex;
            flex-direction: column;
            align-items: center;
            /* center horizontal */
            justify-content: center;
            padding: 2.5rem 2rem 1.5rem;
            background: white;
            text-align: center;
        }


        .login-body {
            padding: 2rem;
        }

        .form-label {
            color: #5a5c69;
            font-weight: 600;
            font-size: 0.95rem;
            margin-bottom: 0.5rem;
        }

        .form-control {
            border: 1px solid #d1d3e2;
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            color: #6e707e;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #4e73df;
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
        }

        .form-control::placeholder {
            color: #b7b9cc;
        }

        .btn-login {
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            border: none;
            color: white;
            padding: 0.875rem 1.5rem;
            font-size: 1rem;
            font-weight: 700;
            border-radius: 0.5rem;
            width: 100%;
            transition: all 0.3s ease;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }

        .btn-login:hover {
            background: linear-gradient(135deg, #224abe 0%, #1e3a8a 100%);
            transform: translateY(-2px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        .alert-danger {
            background-color: #f8d7da;
            border-color: #f5c6cb;
            color: #721c24;
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            margin-bottom: 1rem;
            font-size: 0.9rem;
        }

        .invalid-feedback {
            color: #e74a3b;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        .form-control.is-invalid {
            border-color: #e74a3b;
        }

        .login-footer {
            text-align: center;
            padding: 1.5rem 2rem;
            background: #f8f9fc;
            border-top: 1px solid #e3e6f0;
        }

        .login-footer a {
            color: #4e73df;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .login-footer a:hover {
            color: #224abe;
            text-decoration: underline;
        }

        .input-group {
            position: relative;
        }

        .input-group .form-control {
            padding-right: 45px;
        }

        .input-group .toggle-password {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #858796;
            z-index: 10;
        }

        .input-group .toggle-password:hover {
            color: #4e73df;
        }

        @media (max-width: 576px) {
            .login-header {
                text-align: center;
                padding: 2.5rem 2rem 1.5rem;
                background: white;
            }

            .login-header h4 {
                font-size: 1.25rem;
            }

            .login-body {
                padding: 1.5rem;
            }
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <img src="{{ asset('img/logo.png') }}" alt="Vortex Logo">
                <h4>Welcome Back!</h4>
                <p>Login to continue to your account</p>
            </div>

            <div class="login-body">
                {{ $slot }}
            </div>

            <div class="login-footer">
                <a href="{{ url('/') }}">
                    <i class="fas fa-arrow-left mr-1"></i> Back to Home
                </a>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
