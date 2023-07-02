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
        Schema::create('collection_comments', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("user_id")->nullable();
            $table->bigInteger("user_profile_id")->nullable();
            $table->bigInteger("pre_reg_id")->nullable();
            $table->bigInteger("pre_reg_collige_id")->nullable();
            $table->bigInteger("collection_id")->nullable();
            $table->text("comment")->nullable();
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
        Schema::dropIfExists('collection_comments');
    }
};
