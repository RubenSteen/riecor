<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = \App\Models\User::factory(100)->create();

        foreach ($users as $user) {
            $user->follows()->attach(
                $users->pluck('id')->random(rand(1, 10))->values()->toArray()
            );
        }
    }
}
