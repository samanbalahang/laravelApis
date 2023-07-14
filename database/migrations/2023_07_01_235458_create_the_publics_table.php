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
        Schema::create('the_publics', function (Blueprint $table) {
            $table->id();
            $table->string("welcome_text")->nullable();
            $table->string("employment_text")->nullable();
            $table->string("employment_character")->nullable();
            $table->string("advertisement_right_banner")->nullable();
            $table->string("gallery_character")->nullable();
            $table->string("advertisement_left_banner")->nullable();
            $table->string("gallery_pic")->nullable();
            $table->string("advertisement_banner")->nullable();
            $table->string("welcome_character")->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('the_publics');
    }
};
