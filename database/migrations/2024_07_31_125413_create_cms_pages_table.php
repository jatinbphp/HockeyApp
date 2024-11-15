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
        Schema::create('cms_pages', function (Blueprint $table) {
            $table->id();
            $table->string('page_name')->nullable();
            $table->longText('page_content')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        \Illuminate\Support\Facades\DB::table('cms_pages')->insert([
            'id' => '1',
            'page_name' => 'Contact section above form in App',
            'page_content' => '',
        ]);

        \Illuminate\Support\Facades\DB::table('cms_pages')->insert([
            'id' => '2',
            'page_name' => 'Benefits of Joining',
            'page_content' => '',
        ]);

        \Illuminate\Support\Facades\DB::table('cms_pages')->insert([
            'id' => '3',
            'page_name' => 'How does it work?',
            'page_content' => '',
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cms_pages');
    }
};
