<?php

// database/migrations/xxxx_xx_xx_add_task_date_and_type_to_daily_tasks_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('daily_tasks', function (Blueprint $table) {
            // date this task belongs to (not just when it was completed)
            $table->date('task_date')->after('patient_id')->index();
            // normalized “code” for the task type (morning_medicine, breakfast, etc.)
            $table->string('task_type', 50)->after('task_date')->index();
        });
    }

    public function down(): void
    {
        Schema::table('daily_tasks', function (Blueprint $table) {
            $table->dropColumn(['task_date', 'task_type']);
        });
    }
};

