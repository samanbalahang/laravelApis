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
        Schema::create('post_comments', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("post_id")->nullable();
            $table->string("post_uri")->nullable();
            $table->bigInteger("user_id")->nullable();
            $table->bigInteger("user_profile_id")->nullable();
            $table->bigInteger("user_preReg_id")->nullable();
            $table->bigInteger("user_preRegCol_id")->nullable();
            $table->string("comment")->nullable();
            $table->tinyInteger("approved")->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_comments');
    }
};
