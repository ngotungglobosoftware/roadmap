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
        Schema::create('post', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('authorId');
            $table->foreign('authorId')->references('id')->on('users')->onDelete('cascade');;
            $table->unsignedBigInteger('parentId')->references('id')->on('post');
            $table->string('title', 75);
            $table->string('metaTitle', 100);
            $table->string('slug', 100);
            $table->tinyText('summary');
            $table->tinyText('published', 1);
            $table->dateTime('createdAt', precision: 0);
            $table->dateTime('updatedAt', precision: 0);
            $table->dateTime('publishedAt', precision: 0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post');
    }
};
