<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('rosters', function (Blueprint $table) {
            if (Schema::hasColumn('rosters', 'caregiver_id')) {
                $table->renameColumn('caregiver_id', 'caregiver_1');
            }
        });
    }

    public function down()
    {
        Schema::table('rosters', function (Blueprint $table) {
            if (Schema::hasColumn('rosters', 'caregiver_1')) {
                $table->renameColumn('caregiver_1', 'caregiver_id');
            }
        });
    }
};
