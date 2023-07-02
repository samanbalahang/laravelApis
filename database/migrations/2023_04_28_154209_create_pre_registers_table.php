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
        Schema::create('pre_registers', function (Blueprint $table) {
            $table->id();
            $table->integer("roleID")->nullable();
            $table->string("fullName")->nullable();
            $table->string("fName")->nullable();
            $table->string("lName")->nullable();
            $table->string("nationalCode")->nullable();
            $table->string("phoneNumber")->nullable();
            $table->string("birthDay")->nullable();
            $table->boolean("gender")->nullable();
            $table->string("parentsSituation")->nullable();
            $table->longText("passCode")->nullable();
            $table->string("howKnowUs")->nullable();
            $table->string("profilePhoto")->nullable();
            $table->softDeletes($column = 'deleted_at', $precision = 0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pre_registers');
    }
};
