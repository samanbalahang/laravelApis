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
        Schema::create('media_collections', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("media_id")->nullable();
            $table->bigInteger("collection_id")->nullable();
            $table->bigInteger("collection_cat_id")->nullable();
            $table->integer("user_Id")->nullable();
            $table->integer("userprofile_Id")->nullable();
            $table->integer("class_Id")->nullable();
            $table->integer("turor_Id")->nullable();
            $table->integer("gallery_id")->nullable();
            $table->integer("gallery_cat_id")->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media_collections');
    }
};
