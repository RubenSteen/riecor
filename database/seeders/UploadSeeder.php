<?php

namespace Database\Seeders;

use App\Models\Upload;
use Illuminate\Database\Seeder;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\ConsoleOutput;

class UploadSeeder extends Seeder
{
    private $amount = 20;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $output = new ConsoleOutput();
        $progressBar = new ProgressBar($output, $this->amount);
        $progressBar->setFormat('verbose');
        $progressBar->start();

        $users = Upload::factory($this->amount)->make()->each(function ($upload) use ($progressBar) {
            if ($upload->save()) {
                $progressBar->advance();
            }
        });
    }
}
