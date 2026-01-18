<?php

namespace App\Console\Commands;

use Database\Seeders\WordSeeder;
use Illuminate\Console\Command;

class SeedWordsCommand extends Command
{
    protected $signature = 'words:seed';

    protected $description = 'Seed languages and words from CSV files in database/files/';

    public function handle(): int
    {
        $this->info('Seeding words from CSV files...');

        $seeder = new WordSeeder;
        $seeder->setCommand($this);
        $seeder->run();

        $this->info('Done!');

        return Command::SUCCESS;
    }
}
