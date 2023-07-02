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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->integer("postTypeId")->nullable();
            $table->bigInteger("collection_id")->nullable();
            $table->string("uri")->nullable();
            $table->string("title")->nullable();
            $table->string("description")->nullable();
            $table->longtext("content")->nullable();
            $table->string("photo")->nullable();
            $table->integer("galleryId")->nullable();
            $table->integer("likes")->nullable();
            $table->integer("views")->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
