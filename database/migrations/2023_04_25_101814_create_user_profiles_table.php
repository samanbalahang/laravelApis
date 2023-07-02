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
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();
            $table->integer("user_id")->nullable();
            $table->integer("roleID")->nullable();
            $table->string("fullName")->nullable();
            $table->string("fName")->nullable();
            $table->string("lName")->nullable();
            $table->string("nationalCode")->nullable();
            $table->string("phoneNumber")->nullable();
            $table->string("birthDay")->nullable();
            $table->boolean("gender")->nullable();
            $table->string("passCode")->nullable();
            $table->string("marage")->nullable();
            $table->string("fatherHusbandName")->nullable();
            $table->string("profilePhoto")->nullable();
            $table->string("fatherHusbndphoneNumber")->nullable();
            $table->string("gayemjob")->nullable();
            $table->string("postalCode")->nullable();
            $table->string("insuranceSitu")->nullable();
            $table->string("mainPhoneNumber")->nullable();
            $table->string("askForSallery")->nullable();
            $table->string("email")->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_profiles');
    }
};
