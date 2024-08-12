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
        Schema::create('consent_messages', function (Blueprint $table) {
            $table->id();
            $table->longText('message')->nullable();
            $table->timestamps();
        });

        \Illuminate\Support\Facades\DB::table('consent_messages')->insert([
            'id' => '1',
            'message' => '',
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consent_messages');
    }
};
