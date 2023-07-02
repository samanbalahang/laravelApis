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
        Schema::create('collections', function (Blueprint $table) {
            $table->id();
            $table->string("url")->nullable();
            $table->string("colectName")->nullable();
            $table->string("logo")->nullable();
            $table->string("colectphoto")->nullable();
            $table->text("content")->nullable();
            $table->string("address")->nullable();
            $table->string("map")->nullable();
            $table->string("socialA")->nullable();
            $table->string("socialB")->nullable();
            $table->string("socialC")->nullable();
            $table->string("socialD")->nullable();
            $table->bigInteger("gallery_id")->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collections');
    }
};
