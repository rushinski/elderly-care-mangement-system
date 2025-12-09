<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('doctors', function (Blueprint $table) {
            foreach (['specialization', 'license_number', 'shift'] as $column) {
                if (Schema::hasColumn('doctors', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }

    public function down(): void
    {
        Schema::table('doctors', function (Blueprint $table) {
            $table->string('specialization')->nullable()->after('user_id');
            $table->string('license_number')->nullable()->after('specialization');
            $table->string('shift')->nullable()->after('license_number');
        });
    }
};
