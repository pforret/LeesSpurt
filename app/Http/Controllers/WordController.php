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

        if (! $language) {
            return response()->json([], 404);
        }

        $words = Word::where('language_id', $language->id)
            ->where('length', $length)
            ->pluck('word');

        return response()->json($words);
    }
}
