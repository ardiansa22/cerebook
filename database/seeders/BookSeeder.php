<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\BookGenreCustom;
use App\Models\Category;
use App\Models\Genre;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        $faker = \Faker\Factory::create();

        for ($i = 0; $i < 10; $i++) {
            // Buat category dan genre baru setiap loop (boleh dioptimasi kalau mau)
            $category = Category::create([
                'name' => $faker->word,
            ]);
            $genre = Genre::create([
                'name' => $faker->word,
            ]);
            $book = Book::create([
                'name' => $faker->name,
                'title' => $faker->sentence,
                'description' => $faker->paragraph,
                'price' => $faker->numberBetween(10000, 100000),
                'stock' => $faker->numberBetween(1, 50),
                'category_id' => $category->id, // pakai category yang baru dibuat
                'image' => $faker->imageUrl(640, 480, 'books', true), // gambar faker dengan tema 'books'
            ]);

            // Simpan genre_ids sebagai array string
            $genreIds = [strval($genre->id)];

            BookGenreCustom::create([
                'book_id' => $book->id,
                'genre_ids' => $genreIds,
            ]);
        }
    }
}
