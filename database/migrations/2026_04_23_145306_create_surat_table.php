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
        Schema::create('surat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswa')->onUpdate('cascade')->onDelete('cascade');
            $table->enum('jenis', ['sakit','izin']);
            $table->date('tgl');
            $table->string('foto_surat','255');
            $table->string('keterangan','255')->nullable();
            $table->enum('status',['pending','disetujui','ditolak'])->default('pending');
            $table->string('catatan_konfirmator','255')->nullable();
            $table->foreignId('dikonfirmasi_oleh')->nullable()->constrained('users');
            $table->timestamp('dikonfirmasi_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat');
    }
};
