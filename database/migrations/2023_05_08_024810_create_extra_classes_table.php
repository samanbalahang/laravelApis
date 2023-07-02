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
        Schema::create('extra_classes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("class_id")->nullable();
            $table->integer("week_day_id")->nullable();
            $table->string("StartDate")->nullable();
            $table->string("EndDate")->nullable();
            $table->text("content")->nullable();
            $table->string("sliderPhoto")->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('extra_classes');
    }
};
