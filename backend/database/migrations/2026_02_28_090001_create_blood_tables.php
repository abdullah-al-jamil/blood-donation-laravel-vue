<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blood_donations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('donor_id')->constrained('users')->onDelete('cascade');
            $table->string('blood_type');
            $table->integer('quantity_ml')->default(450);
            $table->date('donation_date');
            $table->enum('status', ['pending', 'completed', 'rejected'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('blood_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('donation_id')->nullable()->constrained('blood_donations')->onDelete('set null');
            $table->string('blood_type');
            $table->integer('quantity_ml')->default(450);
            $table->enum('status', ['pending', 'approved', 'fulfilled', 'rejected'])->default('pending');
            $table->text('reason')->nullable();
            $table->string('hospital')->nullable();
            $table->date('required_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blood_requests');
        Schema::dropIfExists('blood_donations');
    }
};
