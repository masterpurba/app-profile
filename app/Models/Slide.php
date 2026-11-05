<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

#[Fillable([
    'title',
    'description',
    'image',
    'link_url',
    'sort_order',
    'is_active',
    'start_date',
    'end_date',
])]
class Slide extends Model
{
    use HasFactory, HasTranslations;

    public array $translatable = ['title', 'description'];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }
}
