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
        Schema::create('post_comment', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('postId');
            $table->foreign('postId')->references('id')->on('post')->onDelete('cascade');
            $table->unsignedBigInteger('parentId')->references('id')->on('post_comment');
            $table->string('title', 100);
            $table->tinyText('published', 1);
            $table->dateTime('createdAt', precision: 0);
            $table->dateTime('publishedAt', precision: 0);
            $table->text('content', 1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_comment');
    }
};
