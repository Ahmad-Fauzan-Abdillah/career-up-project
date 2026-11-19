<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('job_listings', function (Blueprint $table) {
            $table->string('slug')->nullable();
            $table->enum('status', ['draft', 'terbit', 'ditutup'])->default('draft');
            $table->date('deadline')->nullable();
            $table->unsignedInteger('salary_min')->nullable();
            $table->unsignedInteger('salary_max')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->softDeletes();
            $table->index(['status', 'type']);
            $table->index('type');
            $table->index('deadline');
        });

        foreach (DB::table('job_listings')->select('id', 'title', 'company_name')->get() as $job) {
            $base = Str::slug($job->title.'-'.$job->company_name) ?: 'lowongan';
            $slug = $base;
            $suffix = 2;

            while (DB::table('job_listings')->where('slug', $slug)->where('id', '!=', $job->id)->exists()) {
                $slug = $base.'-'.$suffix++;
            }

            DB::table('job_listings')->where('id', $job->id)->update(['slug' => $slug]);
        }

        Schema::table('job_listings', function (Blueprint $table) {
            $table->unique('slug');
        });

        Schema::table('trainings', function (Blueprint $table) {
            $table->string('slug')->nullable();
            $table->enum('status', ['draft', 'terbit'])->default('draft');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->unsignedInteger('quota')->nullable();
            $table->softDeletes();
            $table->index(['status', 'category']);
            $table->index('category');
            $table->index('schedule_date');
        });

        foreach (DB::table('trainings')->select('id', 'title', 'schedule_time')->get() as $training) {
            $base = Str::slug($training->title) ?: 'pelatihan';
            $slug = $base;
            $suffix = 2;

            while (DB::table('trainings')->where('slug', $slug)->where('id', '!=', $training->id)->exists()) {
                $slug = $base.'-'.$suffix++;
            }

            $times = [];
            if (preg_match('/(\d{1,2})[.:](\d{2})\s*-\s*(\d{1,2})[.:](\d{2})/', (string) $training->schedule_time, $matches)) {
                $times = [
                    'start_time' => sprintf('%02d:%02d:00', $matches[1], $matches[2]),
                    'end_time' => sprintf('%02d:%02d:00', $matches[3], $matches[4]),
                ];
            }

            DB::table('trainings')->where('id', $training->id)->update([
                'slug' => $slug,
                ...$times,
            ]);
        }

        Schema::table('trainings', function (Blueprint $table) {
            $table->unique('slug');
            $table->dropColumn('schedule_time');
        });

        Schema::table('applications', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('status');
        });

        Schema::table('subscriptions', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropIndex('subscriptions_user_id_index');
            $table->dropIndex('subscriptions_status_index');
        });

        Schema::table('applications', function (Blueprint $table) {
            $table->dropIndex('applications_user_id_index');
            $table->dropIndex('applications_status_index');
        });

        Schema::table('trainings', function (Blueprint $table) {
            $table->string('schedule_time')->default('');
        });

        foreach (DB::table('trainings')->select('id', 'start_time', 'end_time')->get() as $training) {
            if ($training->start_time && $training->end_time) {
                DB::table('trainings')->where('id', $training->id)->update([
                    'schedule_time' => substr($training->start_time, 0, 5)
                        .' - '.substr($training->end_time, 0, 5).' WIB',
                ]);
            }
        }

        Schema::table('trainings', function (Blueprint $table) {
            $table->dropUnique('trainings_slug_unique');
            $table->dropIndex('trainings_status_category_index');
            $table->dropIndex('trainings_category_index');
            $table->dropIndex('trainings_schedule_date_index');
            $table->dropColumn(['slug', 'status', 'start_time', 'end_time', 'quota', 'deleted_at']);
        });

        Schema::table('job_listings', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropUnique('job_listings_slug_unique');
            $table->dropIndex('job_listings_status_type_index');
            $table->dropIndex('job_listings_type_index');
            $table->dropIndex('job_listings_deadline_index');
            $table->dropColumn(['slug', 'status', 'deadline', 'salary_min', 'salary_max', 'created_by', 'deleted_at']);
        });
    }
};
