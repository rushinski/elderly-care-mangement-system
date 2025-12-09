<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('daily_tasks', function (Blueprint $table) {
            if (Schema::hasColumn('daily_tasks', 'completed_at')) {
                $table->dropColumn('completed_at');
            }
            if (Schema::hasColumn('daily_tasks', 'remarks')) {
                $table->dropColumn('remarks');
            }
        });
    }

    public function down(): void
    {
        Schema::table('daily_tasks', function (Blueprint $table) {
            $table->timestamp('completed_at')->nullable()->after('completed');
            $table->text('remarks')->nullable()->after('completed_at');
        });
    }
};
