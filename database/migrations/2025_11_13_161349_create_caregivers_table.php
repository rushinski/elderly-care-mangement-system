<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('caregivers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('shift')->nullable(); // Day/Night
            $table->integer('assigned_patients')->default(0);
            $table->string('performance_rating')->nullable(); // e.g., “Excellent”, “Good”, “Needs Improvement”
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('caregivers');
    }
};
