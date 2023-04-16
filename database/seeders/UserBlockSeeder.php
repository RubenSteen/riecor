<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\ConsoleOutput;

class UserBlockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        $output = new ConsoleOutput();
        $progressBar = new ProgressBar($output, count($users));
        $progressBar->setFormat('verbose');
        $progressBar->start();

        // Using transactions to get a bit of more performance
        DB::beginTransaction();

        foreach ($users as $user) {
            $user->blocks()->attach(
                $users->whereNotIn('id', $user->follows->pluck('id'))->pluck('id')->random(rand(1, 10))->values()->toArray()
            );

            $progressBar->advance();
        }
        DB::commit();
    }
}
