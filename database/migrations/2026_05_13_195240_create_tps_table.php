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
        Schema::create('tps', function (Blueprint $table) {
            $table->id();
            $table->string('nama_tps');
            $table->string('kecamatan');
            $table->text('alamat')->nullable();
            $table->string('jadwal')->nullable();
            
            // UBAH DUA BARIS INI:
            $table->decimal('lat', 10, 8)->nullable(); // Latitude lebih akurat
            $table->decimal('lng', 11, 8)->nullable(); // Longitude lebih akurat
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tps');
    }
};