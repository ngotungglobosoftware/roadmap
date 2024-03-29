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
      
        Schema::create('post_category', function (Blueprint $table) {
            $table->unsignedBigInteger('postId');
            $table->foreign('postId')->references('id')->on('post');
            $table->unsignedBigInteger('categoryId');
            $table->foreign('categoryId')->references('id')->on('category');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_category');
    }
};
