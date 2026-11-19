<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('job_listings', function (Blueprint $table) {
            $table->index(['status', 'type', 'deadline'], 'job_listings_publish_type_deadline_index');
        });

        Schema::table('trainings', function (Blueprint $table) {
            $table->index(['status', 'schedule_date'], 'trainings_publish_schedule_index');
        });

        Schema::table('applications', function (Blueprint $table) {
            $table->index(['status', 'created_at'], 'applications_status_created_at_index');
        });

        Schema::table('subscriptions', function (Blueprint $table) {
            $table->index(['status', 'created_at'], 'subscriptions_status_created_at_index');
        });

        Schema::table('user_notifications', function (Blueprint $table) {
            $table->index(['user_id', 'is_read', 'created_at'], 'notifications_user_read_created_index');
        });

        Schema::table('training_enrollments', function (Blueprint $table) {
            $table->index(['training_id', 'certificate_issued'], 'enrollments_training_certificate_index');
        });
    }

    public function down(): void
    {
        Schema::table('training_enrollments', function (Blueprint $table) {
            $table->dropIndex('enrollments_training_certificate_index');
        });

        Schema::table('user_notifications', function (Blueprint $table) {
            $table->dropIndex('notifications_user_read_created_index');
        });

        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropIndex('subscriptions_status_created_at_index');
        });

        Schema::table('applications', function (Blueprint $table) {
            $table->dropIndex('applications_status_created_at_index');
        });

        Schema::table('trainings', function (Blueprint $table) {
            $table->dropIndex('trainings_publish_schedule_index');
        });

        Schema::table('job_listings', function (Blueprint $table) {
            $table->dropIndex('job_listings_publish_type_deadline_index');
        });
    }
};
