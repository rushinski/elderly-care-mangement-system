<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            // Drop unique index on patient_code first (index name from your screenshot)
            if (Schema::hasColumn('patients', 'patient_code')) {
                $table->dropUnique('patients_patient_code_unique');
                $table->dropColumn('patient_code');
            }

            if (Schema::hasColumn('patients', 'medical_history')) {
                $table->dropColumn('medical_history');
            }
        });
    }

    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            // Recreate columns if you ever rollback
            $table->string('patient_code')->nullable()->after('user_id');
            $table->text('medical_history')->nullable()->after('admission_date');
        });
    }
};
