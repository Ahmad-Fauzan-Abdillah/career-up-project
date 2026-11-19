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
        Schema::create('job_listings', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('company_name');
            $table->string('company_logo')->nullable();
            $table->string('location');
            $table->enum('type', ['lowongan', 'magang']);
            $table->string('work_system'); // Full Time, Hybrid, Remote
            $table->string('duration')->nullable();
            $table->string('department')->nullable();
            $table->text('description');
            $table->text('qualifications'); // satu kualifikasi per baris
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_listings');
    }
};
