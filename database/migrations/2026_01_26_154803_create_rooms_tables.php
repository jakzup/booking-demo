<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('rooms', function (Blueprint $table) {
            // this will create an id, a "published" column, and soft delete and timestamps columns
            createDefaultTableFields($table);
            
            $table->decimal('price_per_night', 10, 2)->default(0);
            
            // add those 2 columns to enable publication timeframe fields (you can use publish_start_date only if you don't need to provide the ability to specify an end date)
            // $table->timestamp('publish_start_date')->nullable();
            // $table->timestamp('publish_end_date')->nullable();
        });

        Schema::create('room_translations', function (Blueprint $table) {
            createDefaultTranslationsTableFields($table, 'room');
            $table->string('title', 200)->nullable();
            $table->text('description')->nullable();
        });

        Schema::create('room_slugs', function (Blueprint $table) {
            createDefaultSlugsTableFields($table, 'room');
        });

        
    }

    public function down()
    {
        
        Schema::dropIfExists('room_translations');
        Schema::dropIfExists('room_slugs');
        Schema::dropIfExists('rooms');
    }
};
