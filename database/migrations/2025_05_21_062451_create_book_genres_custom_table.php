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
        Schema::create('book_genres_custom', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('book_id');
        $table->json('genre_ids'); // bisa juga pakai string jika mau pisahkan dengan koma
        $table->timestamps();

        $table->foreign('book_id')->references('id')->on('books')->onDelete('cascade');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_genres_custom');
    }
};
