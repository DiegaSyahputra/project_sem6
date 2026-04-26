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
        Schema::create('surats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswas')->onUpdate('cascade')->onDelete('cascade');
            $table->string('jenis', '10');
            $table->date('tgl_mulai');
            $table->date('tgl_selesai');
            $table->string('foto_surat','255');
            $table->string('keterangan','255')->nullable();
            $table->enum('status',['pending','disetujui','ditolak'])->default('pending');
            $table->string('catatan_konfirmator','255')->nullable();
            $table->foreignId('dikonfirmasi_oleh')->constrained('users');
            $table->timestamp('dikonfirmasi_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surats');
    }
};
