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
        Schema::create('collection_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('collection_id')->constrained('data_collections')->onDelete('cascade');
            $table->string('name'); // Field name (e.g., 'email', 'title')
            $table->string('label'); // Display label
            $table->string('type'); // Data type: text, number, boolean, date, file, relation, json
            $table->text('default_value')->nullable(); // Default value for new records
            $table->boolean('is_required')->default(false);
            $table->boolean('is_unique')->default(false);
            $table->boolean('is_searchable')->default(true);
            $table->json('validation_rules')->nullable(); // Custom validation rules
            $table->json('field_options')->nullable(); // Field-specific options (select options, file types, etc.)
            $table->json('ui_settings')->nullable(); // UI display settings
            $table->foreignId('related_collection_id')->nullable()->constrained('data_collections')->onDelete('set null'); // For relation fields
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['collection_id', 'is_active']);
            $table->index(['collection_id', 'sort_order']);
            $table->unique(['collection_id', 'name']); // Unique field names within collection
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collection_fields');
    }
};