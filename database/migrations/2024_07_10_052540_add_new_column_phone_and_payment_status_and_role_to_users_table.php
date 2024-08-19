<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('image');
            $table->string('role')->nullable()->after('phone');
            $table->string('payment_status')->nullable()->after('role');
        });

        \Illuminate\Support\Facades\DB::table('users')->insert([
            'firstname' => 'Super Admin',
            'lastname' => 'Super Admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('123456'),
            'phone' => '0123456789',
            'status' =>'active',
            'role' => 'super_admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
