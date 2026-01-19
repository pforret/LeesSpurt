<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $language_id
 * @property string $word
 * @property int $length
 * @property int $minimum_age
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \App\Models\Language $language
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Word newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Word newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Word query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Word whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Word whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Word whereLanguageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Word whereLength($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Word whereMinimumAge($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Word whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Word whereWord($value)
 *
 * @mixin \Eloquent
 */
class Word extends Model
{
    use HasFactory;

    protected $fillable = ['language_id', 'word', 'length', 'minimum_age'];

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }
}
