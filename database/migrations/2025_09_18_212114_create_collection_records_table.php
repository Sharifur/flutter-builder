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
        Schema::create('collection_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('collection_id')->constrained('data_collections')->onDelete('cascade');
            $table->uuid('record_uuid')->unique(); // Unique identifier for external API access
            $table->json('status')->nullable(); // Record status info (published, draft, etc.)
            $table->foreignId('created_by')->nullable()->constrained('app_users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('app_users')->onDelete('set null');
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            $table->index(['collection_id', 'created_at']);
            $table->index(['collection_id', 'published_at']);
            $table->index('record_uuid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collection_records');
    }
};