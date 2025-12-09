<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('rosters', function (Blueprint $table) {
            // Drop old FKs that point to supervisors/doctors/caregivers tables
            $table->dropForeign(['supervisor_id']);
            $table->dropForeign(['doctor_id']);
            $table->dropForeign(['caregiver_1']);
            $table->dropForeign(['caregiver_2']);
            $table->dropForeign(['caregiver_3']);
            $table->dropForeign(['caregiver_4']);
        });

        Schema::table('rosters', function (Blueprint $table) {
            // Re-add them pointing to users
            $table->foreign('supervisor_id')
                ->references('id')->on('users')
                ->onDelete('cascade');

            $table->foreign('doctor_id')
                ->references('id')->on('users')
                ->onDelete('cascade');

            $table->foreign('caregiver_1')
                ->references('id')->on('users')
                ->onDelete('set null');

            $table->foreign('caregiver_2')
                ->references('id')->on('users')
                ->onDelete('set null');

            $table->foreign('caregiver_3')
                ->references('id')->on('users')
                ->onDelete('set null');

            $table->foreign('caregiver_4')
                ->references('id')->on('users')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        // optional: restore old FKs if you really need to
    }
};
