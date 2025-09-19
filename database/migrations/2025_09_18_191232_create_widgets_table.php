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
        Schema::create('widgets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('app_page_id')->constrained()->onDelete('cascade');
            $table->string('type'); // Text, Button, Image, Input, Container
            $table->json('config'); // Widget configuration (text, color, action, etc.)
            $table->integer('order')->default(0); // Display order on page
            $table->timestamps();

            $table->index(['app_page_id', 'order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('widgets');
    }
};
