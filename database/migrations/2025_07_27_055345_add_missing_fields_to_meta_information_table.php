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
        Schema::table('meta_information', function (Blueprint $table) {
            $table->string('focus_keyword')->nullable()->after('meta_keywords');
            $table->string('og_site_name')->nullable()->after('og_url');
            $table->integer('seo_score')->nullable()->after('robots');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('meta_information', function (Blueprint $table) {
            $table->dropColumn(['focus_keyword', 'og_site_name', 'seo_score']);
        });
    }
};
