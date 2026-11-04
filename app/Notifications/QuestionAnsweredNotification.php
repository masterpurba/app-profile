<?php

namespace App\Notifications;

use App\Models\Question;
use App\Models\QuestionAnswer;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class QuestionAnsweredNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Question $question,
        public QuestionAnswer $answer
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $cleanAnswer = strip_tags($this->answer->body);

        return (new MailMessage)
            ->subject('Tanggapan Resmi: '.$this->question->subject)
            ->greeting('Yth. Bapak/Ibu '.$this->question->name.',')
            ->line('Terima kasih telah menghubungi layanan pertanyaan masyarakat kami.')
            ->line('Pertanyaan Anda mengenai: **"'.$this->question->subject.'"** telah ditanggapi resmi oleh petugas.')
            ->line('---')
            ->line('**Tanggapan Petugas:**')
            ->line($cleanAnswer)
            ->line('---')
            ->action('Kunjungi Portal Informasi', url('/'))
            ->salutation('Hormat kami,'."\n".config('app.name', 'Tim Layanan Publik'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'question_id' => $this->question->id,
            'question_subject' => $this->question->subject,
            'answer_id' => $this->answer->id,
            'answered_at' => $this->answer->answered_at,
        ];
    }
}
