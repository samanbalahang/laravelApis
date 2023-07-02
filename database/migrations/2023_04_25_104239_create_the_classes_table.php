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
        Schema::create('the_classes', function (Blueprint $table) {
            $table->id();
            $table->string("url")->nullable();
            $table->string("Class_title")->nullable();
            $table->string("className")->nullable();
            $table->string("sliderPhoto")->nullable();
            $table->string("classDescription")->nullable();
            $table->text("content")->nullable();
            $table->string("regStart")->nullable();
            $table->string("regEnd")->nullable();
            $table->string("dateStart")->nullable();
            $table->string("dateEnd")->nullable();
            $table->bigInteger("tutor_id")->nullable();
            $table->string("daysInweek")->nullable();
            $table->string("timeOfDay")->nullable();
            $table->integer("price")->nullable();
            // ظزفیت
            $table->integer("capacity")->nullable();
            $table->string("address")->nullable();
            $table->string("Class_type")->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('the_classes');
    }
};
