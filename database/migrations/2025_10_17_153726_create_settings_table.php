<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->time('jam_masuk_default')->nullable();
            $table->time('jam_pulang_default')->nullable();
            $table->integer('durasi_istirahat')->nullable();

            $table->integer('maks_cuti_tahun')->nullable();
            $table->integer('maks_cuti_bulan')->nullable();
            $table->integer('cuti_minimal_sebelum_pengajuan')->nullable();

            $table->boolean('password_min_8')->default(false);
            $table->boolean('selfie_absensi')->default(false);
            $table->boolean('verifikasi_gps')->default(false);
            $table->boolean('export_mingguan')->default(false);

            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
