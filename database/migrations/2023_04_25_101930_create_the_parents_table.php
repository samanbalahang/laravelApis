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
        Schema::create('the_parents', function (Blueprint $table) {
            $table->id();
            $table->integer("preRegistersId")->nullable();
            $table->integer("studentsId")->nullable();
            $table->string("whoIsParent")->nullable();
            $table->string("fname")->nullable();
            $table->string("lastName")->nullable();
            $table->string("education")->nullable();
            $table->string("career")->nullable();
            $table->string("phoneNumber")->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('the_parents');
    }
};
