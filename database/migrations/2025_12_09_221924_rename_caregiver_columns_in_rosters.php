<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('rosters', function (Blueprint $table) {
            if (Schema::hasColumn('rosters', 'caregiver_1')) {
                $table->renameColumn('caregiver_1', 'caregiver_1_id');
            }
            if (Schema::hasColumn('rosters', 'caregiver_2')) {
                $table->renameColumn('caregiver_2', 'caregiver_2_id');
            }
            if (Schema::hasColumn('rosters', 'caregiver_3')) {
                $table->renameColumn('caregiver_3', 'caregiver_3_id');
            }
            if (Schema::hasColumn('rosters', 'caregiver_4')) {
                $table->renameColumn('caregiver_4', 'caregiver_4_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('rosters', function (Blueprint $table) {
            if (Schema::hasColumn('rosters', 'caregiver_1_id')) {
                $table->renameColumn('caregiver_1_id', 'caregiver_1');
            }
            if (Schema::hasColumn('rosters', 'caregiver_2_id')) {
                $table->renameColumn('caregiver_2_id', 'caregiver_2');
            }
            if (Schema::hasColumn('rosters', 'caregiver_3_id')) {
                $table->renameColumn('caregiver_3_id', 'caregiver_3');
            }
            if (Schema::hasColumn('rosters', 'caregiver_4_id')) {
                $table->renameColumn('caregiver_4_id', 'caregiver_4');
            }
        });
    }
};
