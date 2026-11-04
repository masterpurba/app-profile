<?php

namespace App\Models;

use App\Enums\AnswerStatus;
use App\Enums\QuestionStatus;
use App\Notifications\QuestionAnsweredNotification;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Notification;

#[Fillable([
    'question_id',
    'user_id',
    'body',
    'status',
    'is_published',
    'answered_at',
])]
class QuestionAnswer extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'status' => AnswerStatus::class,
            'is_published' => 'boolean',
            'answered_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::saved(function (self $answer) {
            if ($answer->is_published && $answer->status === AnswerStatus::Published) {
                $question = $answer->question;

                if ($question && $question->status !== QuestionStatus::Answered) {
                    $question->update(['status' => QuestionStatus::Answered]);
                }

                if ($question && ! empty($question->email)) {
                    try {
                        Notification::route('mail', $question->email)
                            ->notify(new QuestionAnsweredNotification($question, $answer));
                    } catch (\Throwable) {
                        // Avoid crashing request if mail driver/server is not configured
                    }
                }
            }
        });
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
