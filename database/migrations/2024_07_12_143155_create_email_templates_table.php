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
        Schema::create('email_templates', function (Blueprint $table) {
            $table->id();
            $table->string('template_name_slug')->nullable();
            $table->string('template_name')->nullable();
            $table->string('template_subject')->nullable();
            $table->longText('template_message')->nullable();
            $table->string('status')->default('inactive');
            $table->softDeletes();
            $table->timestamps();
        });

        \Illuminate\Support\Facades\DB::table('email_templates')->insert([
            'template_name_slug' => 'welcome_register_mail',
            'template_name' => 'Register Template Mail',
            'template_subject' => 'Guardian Registration',
            'template_message' => 'Welcome! Your registration was successful. We are  excited to have you on board!',
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        \Illuminate\Support\Facades\DB::table('email_templates')->insert([
            'template_name_slug' => 'account_active_mail',
            'template_name' => 'Account Active Mail',
            'template_subject' => 'Your Account is Now Active!',
            'template_message' => 'Thank you for your payment! Your account is now active. You can start using all the features immediately.',
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        \Illuminate\Support\Facades\DB::table('email_templates')->insert([
            'template_name_slug' => 'skills_test_mail',
            'template_name' => 'Skills Test Submission Mail',
            'template_subject' => 'Skills Test Submission Received',
            'template_message' => 'Thank you for submitting your skills test. We have received your submission and will review it shortly.',
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_templates');
    }
};
