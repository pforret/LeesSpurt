# PRP: Database Word Storage

## Overview
Store words in the database with fields: `language_id`, `word`, `length`, `minimum_age`. Seed from CSV files at `database/files/words.<language>.csv`.

## Context

### Current State
- Words stored in JSON files: `public/data/thesaurus/<lang>/words-<length>.json`
- Frontend loads via fetch: `/data/thesaurus/${language}/words-${length}.json`
- Languages supported: `en`, `nl`

### Target State
- Words in `words` table with proper indexing
- Languages in `languages` table
- CSV seeding from `database/files/words.nl.csv`, `database/files/words.en.csv`
- API endpoint to serve words (replacing static JSON)

## Implementation Tasks

### 1. Create Languages Migration & Model

**File: `database/migrations/2026_01_18_000001_create_languages_table.php`**
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('languages', function (Blueprint $table) {
            $table->id();
            $table->string('code', 5)->unique(); // 'en', 'nl'
            $table->string('name');              // 'English', 'Dutch'
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('languages');
    }
};
```

**File: `app/Models/Language.php`**
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Language extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'name'];

    public function words(): HasMany
    {
        return $this->hasMany(Word::class);
    }
}
```

### 2. Create Words Migration & Model

**File: `database/migrations/2026_01_18_000002_create_words_table.php`**
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('words', function (Blueprint $table) {
            $table->id();
            $table->foreignId('language_id')->constrained()->cascadeOnDelete();
            $table->string('word', 50);
            $table->unsignedTinyInteger('length');
            $table->unsignedTinyInteger('minimum_age')->default(5);
            $table->timestamps();

            $table->unique(['language_id', 'word']);
            $table->index(['language_id', 'length']);
            $table->index(['language_id', 'length', 'minimum_age']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('words');
    }
};
```

**File: `app/Models/Word.php`**
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Word extends Model
{
    use HasFactory;

    protected $fillable = ['language_id', 'word', 'length', 'minimum_age'];

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }
}
```

### 3. Create CSV Files Directory & Format

**Directory:** `database/files/`

**CSV Format:** `database/files/words.nl.csv`
```csv
word,length,minimum_age
aap,3,5
boom,4,5
fiets,5,5
vlinder,7,6
```

**Minimum Age Rules:**
- Length 2-6: minimum_age = 5
- Length 7+: minimum_age = 6

**CSV Format:** `database/files/words.en.csv`
```csv
word,length,minimum_age
cat,3,5
tree,4,5
house,5,5
bicycle,7,6
```

### 4. Create Seeders

**File: `database/seeders/LanguageSeeder.php`**
```php
<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    public function run(): void
    {
        $languages = [
            ['code' => 'en', 'name' => 'English'],
            ['code' => 'nl', 'name' => 'Dutch'],
        ];

        foreach ($languages as $lang) {
            Language::firstOrCreate(['code' => $lang['code']], $lang);
        }
    }
}
```

**File: `database/seeders/WordSeeder.php`**
```php
<?php

namespace Database\Seeders;

use App\Models\Language;
use App\Models\Word;
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

            if (!file_exists($filePath)) {
                $this->command->warn("File not found: {$filePath}");
                continue;
            }

            $language = $languages->get($langCode);
            if (!$language) {
                continue;
            }

            $this->seedFromCsv($filePath, $language->id);
        }
    }

    private function seedFromCsv(string $filePath, int $languageId): void
    {
        $handle = fopen($filePath, 'r');
        $header = fgetcsv($handle); // Skip header row

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

        if (!empty($batch)) {
            DB::table('words')->insertOrIgnore($batch);
        }

        fclose($handle);
    }
}
```

**Update: `database/seeders/DatabaseSeeder.php`**
```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            LanguageSeeder::class,
            WordSeeder::class,
        ]);
    }
}
```

### 5. Create API Controller

**File: `app/Http/Controllers/WordController.php`**
```php
<?php

namespace App\Http\Controllers;

use App\Models\Language;
use App\Models\Word;
use Illuminate\Http\JsonResponse;

class WordController extends Controller
{
    public function index(string $langCode, int $length): JsonResponse
    {
        $language = Language::where('code', $langCode)->first();

        if (!$language) {
            return response()->json([], 404);
        }

        $words = Word::where('language_id', $language->id)
            ->where('length', $length)
            ->pluck('word');

        return response()->json($words);
    }
}
```

**Add to: `routes/web.php`**
```php
use App\Http\Controllers\WordController;

Route::get('/api/words/{lang}/{length}', [WordController::class, 'index']);
```

### 6. Convert Existing JSON to CSV (One-time)

Run this one-time script to convert existing JSON files:

```bash
php artisan tinker --execute="
\$languages = ['nl'];
foreach (\$languages as \$lang) {
    \$output = [];
    for (\$len = 2; \$len <= 8; \$len++) {
        \$file = public_path(\"data/thesaurus/{\$lang}/words-{\$len}.json\");
        if (file_exists(\$file)) {
            \$words = json_decode(file_get_contents(\$file), true);
            foreach (\$words as \$word) {
                if (strlen(\$word) === \$len) {
                    \$minAge = \$len >= 7 ? 6 : 5;
                    \$output[] = [\$word, \$len, \$minAge];
                }
            }
        }
    }
    \$csv = fopen(database_path(\"files/words.{\$lang}.csv\"), 'w');
    fputcsv(\$csv, ['word', 'length', 'minimum_age']);
    foreach (\$output as \$row) {
        fputcsv(\$csv, \$row);
    }
    fclose(\$csv);
    echo \"Created words.{\$lang}.csv with \" . count(\$output) . \" words\n\";
}
"
```

### 7. Update Frontend to Use API

**File: `resources/js/composables/useThesaurus.ts`**
```typescript
import { ref } from 'vue';

import type { Language } from './useLanguage';

const wordsCache = ref<Record<string, string[]>>({});

export function useThesaurus() {
    const loading = ref(false);
    const error = ref<string | null>(null);

    const loadWords = async (language: Language, length: number): Promise<string[]> => {
        const key = `${language}-${length}`;
        if (wordsCache.value[key]) {
            return wordsCache.value[key];
        }

        try {
            loading.value = true;
            const response = await fetch(`/api/words/${language}/${length}`);
            if (!response.ok) {
                throw new Error(`Failed to load words for ${language} length ${length}`);
            }
            const words = await response.json();
            wordsCache.value[key] = words;
            return words;
        } catch (e) {
            error.value = e instanceof Error ? e.message : 'Failed to load words';
            return [];
        } finally {
            loading.value = false;
        }
    };

    const loadWordsInRange = async (
        language: Language,
        minLength: number,
        maxLength: number
    ): Promise<string[]> => {
        const allWords: string[] = [];
        for (let len = minLength; len <= maxLength; len++) {
            const words = await loadWords(language, len);
            allWords.push(...words);
        }
        return allWords;
    };

    const filterByLetters = (words: string[], knownLetters: string): string[] => {
        const letters = new Set(knownLetters.toLowerCase().split(''));
        return words.filter((word) =>
            word.toLowerCase().split('').every((char) => letters.has(char))
        );
    };

    const selectRandomWord = (words: string[], exclude?: string): string | null => {
        if (words.length === 0) return null;
        if (words.length === 1) return words[0];

        const available = exclude ? words.filter((w) => w !== exclude) : words;
        if (available.length === 0) return words[0];

        return available[Math.floor(Math.random() * available.length)];
    };

    return {
        loading,
        error,
        loadWords,
        loadWordsInRange,
        filterByLetters,
        selectRandomWord,
    };
}
```

### 8. Delete JSON Files (Cleanup)

After successful migration and testing, delete the old JSON files:

```bash
rm -rf public/data/thesaurus/
```

## Validation Gates

```bash
# 1. Lint PHP code
composer lint

# 2. Run migrations
php artisan migrate:fresh

# 3. Run seeders
php artisan db:seed

# 4. Verify data
php artisan tinker --execute="echo 'Languages: ' . App\Models\Language::count() . ', Words: ' . App\Models\Word::count();"

# 5. Test API endpoint
curl http://leestrainer.test/api/words/nl/4

# 6. Run tests
composer test

# 7. Verify frontend loads words (manual test in browser)
# Visit http://leestrainer.test/nl/play/game and verify words appear
```

## File Checklist

- [ ] `database/migrations/2026_01_18_000001_create_languages_table.php`
- [ ] `database/migrations/2026_01_18_000002_create_words_table.php`
- [ ] `app/Models/Language.php`
- [ ] `app/Models/Word.php`
- [ ] `database/seeders/LanguageSeeder.php`
- [ ] `database/seeders/WordSeeder.php`
- [ ] `database/seeders/DatabaseSeeder.php` (update)
- [ ] `database/files/words.nl.csv`
- [ ] `database/files/words.en.csv`
- [ ] `app/Http/Controllers/WordController.php`
- [ ] `routes/web.php` (add API route)
- [ ] `resources/js/composables/useThesaurus.ts` (update)
- [ ] Delete `public/data/thesaurus/` directory

## Patterns Reference

**Migration pattern:** `database/migrations/0001_01_01_000000_create_users_table.php`
**Model pattern:** `app/Models/User.php`
**Seeder pattern:** `database/seeders/DatabaseSeeder.php`

## Documentation

- Laravel Migrations: https://laravel.com/docs/12.x/migrations
- Laravel Seeding: https://laravel.com/docs/12.x/seeding
- Laravel Eloquent: https://laravel.com/docs/12.x/eloquent

## Confidence Score: 9/10

High confidence due to:
- Standard Laravel patterns
- Clear file structure
- Existing codebase patterns to follow
- All requirements clarified

Risks:
- Large CSV files may need chunking optimization (mitigated by batch inserts)
