<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Translatable\HasTranslations;

#[Fillable(['topic_id', 'question', 'answer', 'sort_order', 'is_active'])]
class Faq extends Model
{
    use HasFactory, HasTranslations;

    public array $translatable = ['question', 'answer'];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function topic(): BelongsTo
    {
        return $this->belongsTo(QuestionTopic::class, 'topic_id');
    }
}
