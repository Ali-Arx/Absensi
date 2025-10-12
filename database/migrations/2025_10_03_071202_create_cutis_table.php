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
    Schema::create('cutis', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
         $table->foreignId('approver_id')->nullable()->constrained('users')->onDelete('set null');
        $table->date('tgl_pengajuan');
        $table->date('tgl_mulai');
        $table->date('tgl_selesai');
        $table->string('alasan');
        $table->string('jenis_cuti');
        $table->string('nama_atasan');
        $table->string('tanda_tangan');
        $table->enum('status_pengajuan', ['menunggu', 'disetujui', 'ditolak'])->default('menunggu');
        $table->datetime('tgl_status')->nullable();
        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('cutis');
}

};
