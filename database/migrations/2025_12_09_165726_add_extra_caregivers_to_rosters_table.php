<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('rosters', function (Blueprint $table) {
            if (!Schema::hasColumn('rosters', 'caregiver_2')) {
                $table->unsignedBigInteger('caregiver_2')->nullable()->after('caregiver_1');
            }
            if (!Schema::hasColumn('rosters', 'caregiver_3')) {
                $table->unsignedBigInteger('caregiver_3')->nullable()->after('caregiver_2');
            }
            if (!Schema::hasColumn('rosters', 'caregiver_4')) {
                $table->unsignedBigInteger('caregiver_4')->nullable()->after('caregiver_3');
            }

            $table->foreign('caregiver_2')->references('id')->on('users')->onDelete('set null');
            $table->foreign('caregiver_3')->references('id')->on('users')->onDelete('set null');
            $table->foreign('caregiver_4')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('rosters', function (Blueprint $table) {
            $table->dropForeign(['caregiver_2', 'caregiver_3', 'caregiver_4']);
            $table->dropColumn(['caregiver_2', 'caregiver_3', 'caregiver_4']);
        });
    }
};
