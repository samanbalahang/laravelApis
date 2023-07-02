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
        Schema::create('factors', function (Blueprint $table) {
            $table->id();
            $table->integer("user_id")->nullable();
            $table->integer("user_profile_id")->nullable();
            $table->integer("tuition_id")->nullable();
            $table->integer("financial_id")->nullable();
            $table->integer("salary_id")->nullable();
            $table->string("factorName")->nullable();
            $table->string("factorDesc")->nullable();
            $table->string("basePrice")->nullable();
            $table->string("basePriceDesc")->nullable();
            $table->string("payPrice")->nullable();
            $table->string("payPriceDesc")->nullable();
            $table->string("situtation");
            $table->string("thedate");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('factors');
    }
};
