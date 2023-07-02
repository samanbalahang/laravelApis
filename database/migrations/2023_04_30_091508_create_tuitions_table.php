<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // شهریه
    public function up(): void
    {
        // شهریه
        Schema::create('tuitions', function (Blueprint $table) {
            $table->id();
            $table->integer("class_id")->nullable();
            $table->bigInteger("tuition")->nullable();
            $table->string("theDate")->nullable();
            $table->string("year")->nullable();
            $table->string("month")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tuitions');
    }
};
