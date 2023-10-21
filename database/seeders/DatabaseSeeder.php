<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        DB::table('users')->insert([
            [
                'first_name' => 'Admin',
                'last_name' => '1',
                'email' => 'admin@mail.com',
                'password' => '$2y$10$bDvDFZ4NECV0sWR857CuE.RWu4RCRmAxZzIChuH2KFhvefcifBns6', // hashed 12345678
                'admin' => true,
            ],
        ]);

        DB::table('tags')->insert([
            ['name' => 'Pizza'],
            ['name' => 'Burger'],
            ['name' => 'Sweets'],
            ['name' => 'Breakfast'],
            ['name' => 'Cooked'],
            ['name' => 'Grill'],
            ['name' => 'Vegan'],
            ['name' => 'Drink'],
            ['name' => 'Coffee'],
        ]);

        DB::table('articles')->insert([
            [
                'name' => 'Pizza Capricciosa',
                'description' => 'Indulge in the classic perfection of Pizza Capricciosa—melted mozzarella, savory ham, mushrooms, and zesty tomato sauce. An Italian delight in every bite!',
                'ingredients' => 'Mozzarela cheese, baked ham, mushrooms, tomato',
                'price' => 5.50
            ],
            [
                'name' => 'American hamburger',
                'description' => null,
                'ingredients' => 'Ham pattie, grilled onions, salad, tomato, american chesse',
                'price' => 2.50
            ],
        ]);

        DB::table('article_tags')->insert([
            [
                'article_id' => 1,
                'tag_id' => 1
            ],
            [
                'article_id' => 2,
                'tag_id' => 2
            ],
        ]);
    }
}
