<?php

namespace App\Models;

use App\Enums\PageStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Translatable\HasTranslations;

#[Fillable([
    'user_id',
    'title',
    'slug',
    'content',
    'image',
    'status',
    'sort_order',
    'show_in_menu',
])]
class Page extends Model
{
    use HasFactory, HasTranslations;

    public array $translatable = ['title', 'content'];

    protected function casts(): array
    {
        return [
            'status' => PageStatus::class,
            'show_in_menu' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
