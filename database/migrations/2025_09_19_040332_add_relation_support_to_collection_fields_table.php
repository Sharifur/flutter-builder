<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('collection_fields', function (Blueprint $table) {
            $table->json('relation_config')->nullable()->after('related_collection_id');
            $table->string('relation_type')->nullable()->after('relation_config'); // 'belongsTo', 'hasMany', 'manyToMany'
            $table->string('foreign_key')->nullable()->after('relation_type');
            $table->string('local_key')->nullable()->after('foreign_key');
            $table->boolean('cascade_delete')->default(false)->after('local_key');
        });
    }

    public function down()
    {
        Schema::table('collection_fields', function (Blueprint $table) {
            $table->dropColumn(['relation_config', 'relation_type', 'foreign_key', 'local_key', 'cascade_delete']);
        });
    }
};