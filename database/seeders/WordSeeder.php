<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WordSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(LanguageSeeder::class);

        $languages = Language::all()->keyBy('code');

        foreach (['nl', 'en'] as $langCode) {
            $filePath = database_path("files/words.{$langCode}.csv");

            if (! file_exists($filePath)) {
                $this->command->warn("File not found: {$filePath}");

                continue;
            }

            $language = $languages->get($langCode);
            if (! $language) {
                continue;
            }

            $this->seedFromCsv($filePath, $language->id);
        }
    }

    private function seedFromCsv(string $filePath, int $languageId): void
    {
        $handle = fopen($filePath, 'r');
        fgetcsv($handle); // Skip header row

        $batch = [];
        $batchSize = 500;
        $now = now();

        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) < 3) {
                continue;
            }

            $batch[] = [
                'language_id' => $languageId,
                'word' => trim($row[0]),
                'length' => (int) $row[1],
                'minimum_age' => (int) $row[2],
                'created_at' => $now,
                'updated_at' => $now,
            ];

            if (count($batch) >= $batchSize) {
                DB::table('words')->insertOrIgnore($batch);
                $batch = [];
            }
        }

        if (! empty($batch)) {
            DB::table('words')->insertOrIgnore($batch);
        }

        fclose($handle);
    }
}
