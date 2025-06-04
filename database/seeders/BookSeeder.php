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
            // Simpan buku
            $category = Category::create([
                'name' => $faker->name,
            ]);
            $genre = Genre::create([
                'name' => $faker->name,
            ]);
            $book = Book::create([
                'name' => $faker->name,
                'title' => $faker->sentence,
                'description' => $faker->paragraph,
                'price' => $faker->numberBetween(10000, 100000),
                'stock' => $faker->numberBetween(1, 50),
                'category_id' => 1, // sesuaikan dengan kategori yang ada
            ]);

            // Simpan genre_ids (simulasi ID genre sebagai string, misalnya "1", "2", dst)
            $genreIds = [strval($faker->numberBetween(1, 5))]; // contoh 1 genre

            BookGenreCustom::create([
                'book_id' => $book->id,
                'genre_ids' => $genreIds,
            ]);
        }
    }
}
