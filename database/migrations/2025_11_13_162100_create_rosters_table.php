<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rosters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supervisor_id')->constrained('supervisors')->onDelete('cascade');
            $table->foreignId('doctor_id')->nullable()->constrained('doctors')->onDelete('cascade');
            $table->foreignId('caregiver_id')->nullable()->constrained('caregivers')->onDelete('cascade');
            $table->date('date');
            $table->string('shift')->default('Day'); // Day/Night
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rosters');
    }
};
