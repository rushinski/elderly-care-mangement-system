<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Ensure DOB column exists on users (if you don't already have it)
            if (!Schema::hasColumn('users', 'date_of_birth')) {
                $table->date('date_of_birth')->nullable()->after('address');
            }

            // Remove patient-specific columns from users if they exist
            if (Schema::hasColumn('users', 'family_code')) {
                $table->dropColumn('family_code');
            }
            if (Schema::hasColumn('users', 'emergency_contact')) {
                $table->dropColumn('emergency_contact');
            }
            if (Schema::hasColumn('users', 'emergency_contact_relation')) {
                $table->dropColumn('emergency_contact_relation');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Optional: restore dropped columns
            if (!Schema::hasColumn('users', 'family_code')) {
                $table->string('family_code')->nullable();
            }
            if (!Schema::hasColumn('users', 'emergency_contact')) {
                $table->string('emergency_contact')->nullable();
            }
            if (!Schema::hasColumn('users', 'emergency_contact_relation')) {
                $table->string('emergency_contact_relation')->nullable();
            }

            // You can drop date_of_birth here if you really want to fully revert,
            // but typically you'll keep it.
        });
    }
};
