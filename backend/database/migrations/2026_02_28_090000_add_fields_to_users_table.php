<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['donor', 'recipient', 'admin'])->default('donor')->after('email');
            $table->string('phone')->nullable()->after('role');
            $table->string('address')->nullable()->after('phone');
            $table->date('date_of_birth')->nullable()->after('address');
            $table->string('blood_type')->nullable()->after('date_of_birth');
            $table->boolean('is_eligible')->default(true)->after('blood_type');
            $table->timestamp('last_donation_date')->nullable()->after('is_eligible');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'phone', 'address', 'date_of_birth', 'blood_type', 'is_eligible', 'last_donation_date']);
        });
    }
};
