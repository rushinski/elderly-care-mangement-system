<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('prescriptions', function (Blueprint $table) {
            $table->string('morning_med')->nullable()->after('medicine_name');
            $table->string('afternoon_med')->nullable()->after('morning_med');
            $table->string('night_med')->nullable()->after('afternoon_med');
            $table->text('comment')->nullable()->after('instructions');
        });
    }

    public function down(): void
    {
        Schema::table('prescriptions', function (Blueprint $table) {
            $table->dropColumn(['morning_med', 'afternoon_med', 'night_med', 'comment']);
        });
    }
};
