<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\ConsoleOutput;

class UserViewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $users_count = $users->count();

        $output = new ConsoleOutput();
        $progressBar = new ProgressBar($output, count($users));
        $progressBar->setFormat('verbose');
        $progressBar->start();

        // Using transactions to get a bit of more performance
        DB::beginTransaction();

        foreach ($users as $user) {
            $user->views()->attach(
                $users->pluck('id')->random(rand(1, $users_count))->values()->toArray()
            );

            $progressBar->advance();
        }
        DB::commit();
    }
}
