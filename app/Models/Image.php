<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Image extends Model
{
    use HasFactory;

    protected $fillable = [
        'prompt',
        'keywords',
        'path',
        'progress',
        'status',
        'user_id',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope(
            'user_id',
            fn (Builder $builder) => $builder->where('user_id', auth()->id())
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
