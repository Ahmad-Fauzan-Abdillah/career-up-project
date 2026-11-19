<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_notifications', function (Blueprint $table) {
            $table->string('url')->nullable()->after('message');
        });

        Schema::table('applications', function (Blueprint $table) {
            $table->enum('status', ['Diproses', 'Diterima', 'Ditolak', 'Dibatalkan'])->default('Diproses')->change();
        });

        Schema::table('subscriptions', function (Blueprint $table) {
            $table->enum('status', ['pending', 'aktif', 'ditolak', 'kedaluwarsa'])->default('pending')->change();
            $table->date('valid_until')->nullable()->change();
            $table->string('payment_proof')->nullable()->after('payment_method');
            $table->unsignedSmallInteger('unique_code')->default(0)->after('payment_proof');
            $table->foreignId('confirmed_by')->nullable()->after('user_id')->constrained('users')->nullOnDelete();
            $table->timestamp('confirmed_at')->nullable()->after('valid_until');
            $table->text('rejection_reason')->nullable()->after('confirmed_at');
        });

        Schema::table('training_enrollments', function (Blueprint $table) {
            $table->timestamp('reminder_sent_at')->nullable();
            $table->timestamp('certificate_issued_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('training_enrollments', function (Blueprint $table) {
            $table->dropColumn(['reminder_sent_at', 'certificate_issued_at']);
        });

        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropForeign(['confirmed_by']);
            $table->dropColumn(['payment_proof', 'unique_code', 'confirmed_at', 'rejection_reason', 'confirmed_by']);
            $table->enum('status', ['pending', 'aktif'])->default('aktif')->change();
            $table->date('valid_until')->nullable(false)->change();
        });

        Schema::table('applications', function (Blueprint $table) {
            $table->enum('status', ['Diproses', 'Diterima', 'Ditolak'])->default('Diproses')->change();
        });

        Schema::table('user_notifications', function (Blueprint $table) {
            $table->dropColumn('url');
        });
    }
};
