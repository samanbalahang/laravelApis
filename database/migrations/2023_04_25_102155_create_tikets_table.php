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
        Schema::create('tikets', function (Blueprint $table) {
            $table->id();
            $table->integer("userId")->nullable();
            $table->integer("userprofileId")->nullable();
            $table->string("subject")->nullable();
            $table->string("tiketFile")->nullable();
            $table->longText("tiketContent")->nullable();   
            $table->integer("whoAnswer")->nullable();   
            $table->softDeletes();         
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tikets');
    }
};
