<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('prescriptions', function (Blueprint $table) {
            $table->dropColumn([
                'medicine_name', 'dosage', 'frequency', 'duration_days', 'instructions'
            ]);
        });
    }

    public function down()
    {
        Schema::table('prescriptions', function (Blueprint $table) {
            $table->string('medicine_name')->nullable();
            $table->string('dosage')->nullable();
            $table->string('frequency')->nullable();
            $table->integer('duration_days')->nullable();
            $table->text('instructions')->nullable();
        });
    }

};
