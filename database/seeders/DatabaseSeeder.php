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
    }
}
