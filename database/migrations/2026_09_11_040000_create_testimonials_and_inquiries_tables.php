<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('testimonials', function (Blueprint $table): void {
            $table->id();
            $table->string('customer_name');
            $table->string('city')->nullable();
            $table->string('title')->nullable();
            $table->text('body');
            $table->unsignedTinyInteger('rating')->default(5);
            $table->unsignedInteger('sort_order')->default(0)->index();
            $table->boolean('is_featured')->default(false)->index();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });

        Schema::create('inquiries', function (Blueprint $table): void {
            $table->id();
            $table->enum('type', ['contact', 'custom_order'])->index();
            $table->string('name');
            $table->string('phone')->index();
            $table->string('email')->nullable();
            $table->string('subject')->nullable();
            $table->text('message')->nullable();
            $table->json('meta')->nullable();
            $table->string('status')->default('new')->index();
            $table->timestamp('handled_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inquiries');
        Schema::dropIfExists('testimonials');
    }
};
