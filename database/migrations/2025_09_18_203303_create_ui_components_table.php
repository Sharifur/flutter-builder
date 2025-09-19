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
        Schema::create('ui_components', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('component_type');
            $table->string('category')->default('general');
            $table->text('description')->nullable();
            $table->json('default_config');
            $table->json('field_definitions');
            $table->string('icon')->nullable();
            $table->string('preview_image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->string('php_class');
            $table->json('dependencies')->nullable();
            $table->timestamps();

            $table->unique(['component_type']);
            $table->index(['category', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ui_components');
    }
};
