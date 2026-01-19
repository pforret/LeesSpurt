<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WordSeeder extends Seeder
{
    private const LANGUAGE_NAMES = [
        'en' => 'English',
        'nl' => 'Dutch',
        'fr' => 'French',
        'de' => 'German',
        'es' => 'Spanish',
        'it' => 'Italian',
        'pt' => 'Portuguese',
    ];

    public function run(): void
    {
        DB::table('words')->truncate();

        $files = glob(database_path('files/words.*.csv'));

        foreach ($files as $filePath) {
            // Extract language code from filename (words.XX.csv)
            if (! preg_match('/words\.([a-z]{2})\.csv$/', $filePath, $matches)) {
                continue;
            }

            $langCode = $matches[1];
            $langName = self::LANGUAGE_NAMES[$langCode] ?? ucfirst($langCode);

            $language = Language::firstOrCreate(
                ['code' => $langCode],
                ['name' => $langName]
            );

            $this->seedFromCsv($filePath, $language->id);
            $this->command->info("Seeded words for {$langName}");
        }
    }

    private function seedFromCsv(string $filePath, int $languageId): void
    {
        $handle = fopen($filePath, 'r');
        fgetcsv($handle); // Skip header row

        $batch = [];
        $batchSize = 100;
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
                DB::table('words')->upsert($batch, ['language_id', 'word'], ['length', 'minimum_age', 'updated_at']);
                $batch = [];
            }
        }

        if (! empty($batch)) {
            DB::table('words')->upsert($batch, ['language_id', 'word'], ['length', 'minimum_age', 'updated_at']);
        }

        fclose($handle);
    }
}
