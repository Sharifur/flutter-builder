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
        Schema::create('collection_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('collection_id')->constrained('data_collections')->onDelete('cascade');
            $table->foreignId('record_id')->constrained('collection_records')->onDelete('cascade');
            $table->foreignId('field_id')->constrained('collection_fields')->onDelete('cascade');
            $table->text('field_value')->nullable(); // Store actual field value as text
            $table->string('field_type'); // Store the data type for proper casting
            $table->json('field_metadata')->nullable(); // Additional metadata for complex fields
            $table->timestamps();

            $table->index(['collection_id', 'record_id']);
            $table->index(['record_id', 'field_id']);
            $table->index(['collection_id', 'field_id']);
            $table->unique(['record_id', 'field_id']); // One value per field per record
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collection_data');
    }
};