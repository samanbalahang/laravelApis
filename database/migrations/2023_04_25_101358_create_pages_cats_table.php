<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations :
     * این مایگریشن برای ایجاد 
     * دسته بندی پست ها کاربرد دارد
     * صفحاتی مانند اخبار و مقالات را 
     * با کمک این دسته بندی ها ایجاد 
     * کرده ایم.
     */

    public function up(): void
    {
        Schema::create('pages_cats', function (Blueprint $table) {
            $table->id();
            $table->string("catName");
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pages_cats');
    }
};
