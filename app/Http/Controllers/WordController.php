<?php

namespace App\Http\Controllers;

use App\Models\Language;
use App\Models\Word;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WordController extends Controller
{
    public function index(Request $request, string $langCode, int $length): JsonResponse
    {
        $language = Language::where('code', $langCode)->first();

        if (! $language) {
            return response()->json([], 404);
        }

        $query = Word::where('language_id', $language->id)
            ->where('length', $length);

        if ($request->has('max_age')) {
            $query->where('minimum_age', '<=', (int) $request->get('max_age'));
        }

        $words = $query->pluck('word');

        return response()->json($words);
    }
}
