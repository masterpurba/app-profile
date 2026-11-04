<?php

namespace App\Models;

use App\Enums\QuestionStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'topic_id',
    'name',
    'email',
    'phone',
    'subject',
    'body',
    'status',
    'ip_address',
])]
class Question extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'status' => QuestionStatus::class,
        ];
    }

    public function topic(): BelongsTo
    {
        return $this->belongsTo(QuestionTopic::class, 'topic_id');
    }

    public function answers(): HasMany
    {
        return $this->hasMany(QuestionAnswer::class);
    }
}
