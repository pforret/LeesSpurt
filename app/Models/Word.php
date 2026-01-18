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
