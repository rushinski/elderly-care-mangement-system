<?php
// database/migrations/2025_12_08_155300_update_users_move_patient_fields.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->string('family_code')->nullable()->after('patient_code');
            $table->string('emergency_contact')->nullable()->after('family_code');
            $table->string('emergency_contact_relation')->nullable()->after('emergency_contact');
        });
    }

    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn([
                'family_code',
                'emergency_contact',
                'emergency_contact_relation',
            ]);
        });
    }
};
