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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('nip')->unique();
            $table->string('name');
            $table->date('birth_date');
            $table->enum('gender', ['l', 'p'])->default('l');
            $table->enum('status', ['active', 'nonactive'])->default('active');
            $table->enum('education_level', ['SD', 'SMP', 'SMA', 'D1', 'D2', 'D3', 'D4', 'S1', 'S2', 'S3'])->nullable();
            $table->string('education_detail')->nullable();
            $table->date('tmt_start')->default(now());
            $table->date('tmt_end')->nullable();
            $table->date('tmt_kgb')->nullable();
            $table->enum('type', ['Non ASN', 'ASN'])->default('Non ASN');

            // RELASI
            $table->foreignId('rank_grade_id')
                ->nullable()
                ->constrained('rank_grades')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('position_id')
                ->constrained('positions')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
