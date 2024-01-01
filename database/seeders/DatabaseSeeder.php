<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Review;
use Illuminate\Database\Seeder;
use App\Models\Book;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Book::factory(33)->create()->each(function($book){
            $numReview = random_int(5, 30);

            Review::factory()->count($numReview)->good()->for($book)->create();
        });
        Book::factory(34)->create()->each(function($book){
            $numReview = random_int(5, 30);

            Review::factory()->count($numReview)->bad()->for($book)->create();
        });
        Book::factory(33)->create()->each(function($book){
            $numReview = random_int(5, 30);

            Review::factory()->count($numReview)->average()->for($book)->create();
        });
    }
}
