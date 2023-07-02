<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /*----------------------------*
    | 
    | 
    *    Run the migrations.
    |    امکان ایجاد فوق برنامه
    |
     -----------------------------*/
    public function up(): void
    {
        Schema::create('extras', function (Blueprint $table) {
            $table->id();
            $table->string("extraName")->nullable();            
            $table->string("extraDesc")->nullable();            
            $table->string("extraContent")->nullable();            
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('extras');
    }
};
