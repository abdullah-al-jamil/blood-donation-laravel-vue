<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('donor')->after('password');
            $table->string('blood_type')->nullable()->after('role');
            $table->string('phone')->nullable()->after('blood_type');
            $table->date('dob')->nullable()->after('phone');
            $table->text('address')->nullable()->after('dob');
            $table->boolean('is_eligible')->default(true)->after('address');
            $table->datetime('last_donation_at')->nullable()->after('is_eligible');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'role',
                'blood_type',
                'phone',
                'dob',
                'address',
                'is_eligible',
                'last_donation_at',
            ]);
        });
    }
};
