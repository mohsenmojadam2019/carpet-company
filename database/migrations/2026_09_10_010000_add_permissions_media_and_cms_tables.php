<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('permissions')) Schema::create('permissions', function (Blueprint $table): void { $table->id(); $table->string('name'); $table->string('guard_name'); $table->timestamps(); $table->unique(['name','guard_name']); });
        if (!Schema::hasTable('roles')) Schema::create('roles', function (Blueprint $table): void { $table->id(); $table->string('name'); $table->string('guard_name'); $table->timestamps(); $table->unique(['name','guard_name']); });
        if (!Schema::hasTable('model_has_permissions')) Schema::create('model_has_permissions', function (Blueprint $table): void { $table->unsignedBigInteger('permission_id'); $table->string('model_type'); $table->unsignedBigInteger('model_id'); $table->index(['model_id','model_type'], 'model_has_permissions_model_id_model_type_index'); $table->foreign('permission_id')->references('id')->on('permissions')->cascadeOnDelete(); $table->primary(['permission_id','model_id','model_type'], 'model_has_permissions_permission_model_type_primary'); });
        if (!Schema::hasTable('model_has_roles')) Schema::create('model_has_roles', function (Blueprint $table): void { $table->unsignedBigInteger('role_id'); $table->string('model_type'); $table->unsignedBigInteger('model_id'); $table->index(['model_id','model_type'], 'model_has_roles_model_id_model_type_index'); $table->foreign('role_id')->references('id')->on('roles')->cascadeOnDelete(); $table->primary(['role_id','model_id','model_type'], 'model_has_roles_role_model_type_primary'); });
        if (!Schema::hasTable('role_has_permissions')) Schema::create('role_has_permissions', function (Blueprint $table): void { $table->unsignedBigInteger('permission_id'); $table->unsignedBigInteger('role_id'); $table->foreign('permission_id')->references('id')->on('permissions')->cascadeOnDelete(); $table->foreign('role_id')->references('id')->on('roles')->cascadeOnDelete(); $table->primary(['permission_id','role_id'], 'role_has_permissions_permission_id_role_id_primary'); });
        if (!Schema::hasTable('media')) Schema::create('media', function (Blueprint $table): void { $table->id(); $table->morphs('model'); $table->uuid('uuid')->nullable()->unique(); $table->string('collection_name'); $table->string('name'); $table->string('file_name'); $table->string('mime_type')->nullable(); $table->string('disk'); $table->string('conversions_disk')->nullable(); $table->unsignedBigInteger('size'); $table->json('manipulations'); $table->json('custom_properties'); $table->json('generated_conversions'); $table->json('responsive_images'); $table->unsignedInteger('order_column')->nullable()->index(); $table->nullableTimestamps(); });
        if (!Schema::hasTable('settings')) Schema::create('settings', function (Blueprint $table): void { $table->id(); $table->string('group')->default('general')->index(); $table->string('key')->unique(); $table->text('value')->nullable(); $table->string('type', 30)->default('string'); $table->boolean('is_public')->default(true)->index(); $table->timestamps(); });
        if (!Schema::hasTable('menu_items')) Schema::create('menu_items', function (Blueprint $table): void { $table->id(); $table->foreignId('parent_id')->nullable()->constrained('menu_items')->cascadeOnDelete(); $table->string('label'); $table->string('url', 500)->nullable(); $table->string('route_name')->nullable(); $table->unsignedTinyInteger('column')->default(1); $table->unsignedInteger('sort_order')->default(0); $table->boolean('is_active')->default(true)->index(); $table->timestamps(); $table->index(['parent_id','column','sort_order']); });
        if (!Schema::hasTable('media_assets')) Schema::create('media_assets', function (Blueprint $table): void { $table->id(); $table->string('title')->nullable(); $table->timestamps(); });
    }

    public function down(): void
    {
        Schema::dropIfExists('media_assets'); Schema::dropIfExists('menu_items'); Schema::dropIfExists('settings'); Schema::dropIfExists('media'); Schema::dropIfExists('role_has_permissions'); Schema::dropIfExists('model_has_roles'); Schema::dropIfExists('model_has_permissions'); Schema::dropIfExists('roles'); Schema::dropIfExists('permissions');
    }
};
