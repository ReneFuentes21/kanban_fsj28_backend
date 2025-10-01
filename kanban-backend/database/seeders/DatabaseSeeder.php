<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Seed boards
        DB::table('boards')->insert([
            ['name' => 'Board 1'],
            ['name' => 'Board 2'],
        ]);

        // Seed cards
        DB::table('cards')->insert([
            ['title' => 'Card 1', 'board_id' => 1],
            ['title' => 'Card 2', 'board_id' => 1],
            ['title' => 'Card 3', 'board_id' => 2],
        ]);

        // Seed tasks
        DB::table('tasks')->insert([
            ['description' => 'Task 1', 'card_id' => 1],
            ['description' => 'Task 2', 'card_id' => 1],
            ['description' => 'Task 3', 'card_id' => 2],
            ['description' => 'Task 4', 'card_id' => 3],
        ]);
    }
}