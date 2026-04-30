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
        Schema::create('jadwal', function (Blueprint $table) {
            $table->id();
            $table->time('jam');
            $table->char('durasi','2');
            $table->enum('hari',['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu']);
            $table->foreignId('dosen_id')->constrained('dosen');
            $table->foreignId('prodi_id')->constrained('prodi');
            $table->foreignId('matkul_id')->constrained('matkul');
            $table->foreignId('ruangan_id')->constrained('ruangan');
            $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajaran');
            $table->tinyInteger('semester');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal');
    }
};
