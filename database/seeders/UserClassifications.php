<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserClassifications extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = DB::table('users')->limit(3)->get();
        $glitches = DB::table('glitches')->limit(5)->get();

        foreach ($users as $user) {
            foreach ($glitches as $glitch) {
                DB::table('user_classifications')->insert([
                    'user_id' => $user->id,
                    'glitch_id' => $glitch->id,
                    'glitch_type_id' => $glitch->glitch_type_id,
                    'confidence' => rand(60, 100) / 100,
                    'time_spent_seconds' => rand(5, 120),
                    'notes' => 'Classificação automática de teste.'
                ]);
            }
        }
    }
}
