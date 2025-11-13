<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->onDelete('cascade');
            $table->decimal('daily_rate', 8, 2)->default(10.00);
            $table->decimal('appointment_rate', 8, 2)->default(50.00);
            $table->decimal('medicine_rate', 8, 2)->default(15.00);
            $table->integer('days')->default(0);
            $table->integer('appointments')->default(0);
            $table->decimal('total_amount', 10, 2)->default(0.00);
            $table->string('status')->default('Unpaid'); // Unpaid / Paid
            $table->date('payment_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
