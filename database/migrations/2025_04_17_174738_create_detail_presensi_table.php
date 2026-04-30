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
        Schema::create('detail_presensi', function (Blueprint $table) {
            $table->foreignId('presensi_id')->constrained('presensi');
            $table->foreignId('mahasiswa_id')->constrained('mahasiswa');
            $table->dateTime('waktu_presensi')->nullable();
            $table->boolean('status')->default(false);
            $table->string('alasan')->nullable();
            $table->string('bukti',100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_presensi');
    }
};
