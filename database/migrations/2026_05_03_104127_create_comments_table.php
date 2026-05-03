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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            // Menghubungkan komentar ke laporan (report)
            // onDelete('cascade') artinya jika laporan dihapus, komentarnya ikut terhapus
            $table->foreignId('report_id')->constrained('reports')->onDelete('cascade');
            
            // Menghubungkan komentar ke user (siapa yang berkomentar)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // Isi komentarnya
            $table->text('body');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};