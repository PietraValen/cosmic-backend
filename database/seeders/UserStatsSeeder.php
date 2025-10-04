<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserStatsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userId = DB::table('users')->where('email', 'admin@cosmic.com')->value('id');

        DB::table('user_stats')->insert([
            'user_id' => $userId,
            'total_classifications' => rand(10, 100),
            'correct_classifications' => rand(5, 50),
            'accuracy' => rand(50, 100) / 100,
            'points' => rand(100, 1000),
            'level' => rand(1, 5),
            'badges' => json_encode(['starter', 'contributor']),
            'streak_days' => rand(0, 30),
            'last_classification_at' => now(),
        ]);
    }
}
