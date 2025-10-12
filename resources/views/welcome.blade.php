@extends('layouts.welcome')

@section('title', 'e-Absence, Leave, & Overtime - PT. Vortex Energy Batam')

@section('content')
<!-- Main Content -->
<main class="main-content">
    <div class="container">
        <h1 class="main-title">e-Absence, Leave, & Overtime</h1>
        <p class="main-subtitle">PT. Vortex Energy Batam</p>
        <p class="main-description">
            adalah sistem pencatatan absensi,<br>
            pengajuan cuti dan lembur<br>
            berbasis website
        </p>
    </div>
</main>

<!-- Features Section -->
<section class="features-section">
    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <h4>Absensi Digital</h4>
                    <p>Catat kehadiran secara online dengan mudah dan cepat</p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <h4>Pengajuan Cuti</h4>
                    <p>Ajukan cuti kapan saja dan pantau statusnya real-time</p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-business-time"></i>
                    </div>
                    <h4>Lembur</h4>
                    <p>Kelola pengajuan lembur dengan sistem yang terorganisir</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection