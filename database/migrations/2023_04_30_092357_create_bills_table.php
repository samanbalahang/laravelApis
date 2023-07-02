<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // صورت حساب
    public function up(): void
    {
        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->integer("user_id")->nullable();
            $table->integer("user_profile_id")->nullable();
            $table->integer("tuition_id")->nullable();
            $table->integer("financial_id")->nullable();
            $table->integer("factor_id")->nullable();
            $table->integer("salary")->nullable();
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
        Schema::dropIfExists('bills');
    }
};
