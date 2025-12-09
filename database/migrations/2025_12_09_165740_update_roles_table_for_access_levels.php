<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            // numeric access level, lower = more power (0 = Admin)
            $table->unsignedInteger('access_level')->default(99)->after('name');

            // remove old description column
            $table->dropColumn('description');
        });
    }

    public function down(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            // restore description if you ever roll back
            $table->string('description')->nullable();
            $table->dropColumn('access_level');
        });
    }
};