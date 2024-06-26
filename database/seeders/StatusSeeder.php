<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('mstatuses')->insert([
            [
                'title' => 'To Do',
                'slug' => 'to-do'
            ],
            [
                'title' => 'Progress',
                'slug' => 'in-progress'
            ],
            [
                'title' => 'Done',
                'slug' => 'done'
            ]
        ]);
    }
}
