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
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->longText('content')->nullable();
            $table->timestamps();
        });

        \Illuminate\Support\Facades\DB::table('consent_messages')->insert([
            'id' => '1',
            'title' => 'Terms & Conditions',
            'content' => 'Terms & Conditions',
        ]);

        \Illuminate\Support\Facades\DB::table('consent_messages')->insert([
            'id' => '2',
            'title' => 'Privacy Policy',
            'content' => 'privacy policy',
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
