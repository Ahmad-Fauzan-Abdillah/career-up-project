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
        Schema::create('trainings', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->enum('category', ['Gratis', 'Premium']);
            $table->date('schedule_date');
            $table->string('schedule_time'); // e.g. "07.00 - 11.00 WIB"
            $table->string('platform')->default('Zoom');
            $table->string('zoom_link')->nullable();
            $table->string('mentor_name');
            $table->string('mentor_role');
            $table->string('mentor_avatar')->nullable();
            $table->string('duration'); // e.g. "8 Pertemuan"
            $table->string('level'); // e.g. "Menengah - Mahir"
            $table->text('description');
            $table->text('syllabus'); // satu materi per baris
            $table->string('thumbnail')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trainings');
    }
};
