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
        Schema::create('skills', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->integer('category_id')->default(0);
            $table->longText('short_description')->nullable();
            $table->longText('long_description')->nullable();
            $table->longText('instruction')->nullable();
            $table->longText('score_instruction')->nullable();
            $table->text('video_url')->nullable();
            $table->string('featured_image')->nullable();
            $table->integer('position')->default(99999);
            $table->string('status')->default('active');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skills');
    }
};
