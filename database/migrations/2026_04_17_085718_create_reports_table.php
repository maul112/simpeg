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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            // TAMBAHKAN BARIS INI:
            $table->string('tracking_id')->unique(); 
            
            $table->string('nama_pelapor');
            $table->string('kontak')->nullable();
            $table->text('deskripsi')->nullable();
            $table->enum('tipe_sampah', ['organik', 'non_organik'])->default('organik');
            $table->text('lokasi_manual')->nullable();
            $table->string('foto_bukti');
            
            // Koordinat Peta
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            
            $table->enum('status', ['pending', 'proses', 'selesai'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};