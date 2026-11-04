<?php

namespace Tests\Feature;

use App\Enums\AnswerStatus;
use App\Enums\QuestionStatus;
use App\Models\Question;
use App\Models\QuestionAnswer;
use App\Models\QuestionTopic;
use App\Models\User;
use App\Notifications\QuestionAnsweredNotification;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ApiEndpointsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_can_fetch_posts(): void
    {
        $response = $this->getJson('/api/v1/posts');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'title', 'slug', 'summary', 'status'],
                ],
            ]);
    }

    public function test_can_fetch_post_detail_and_increments_views(): void
    {
        $response = $this->getJson('/api/v1/posts/peluncuran-layanan-digital-terpadu');

        $response->assertStatus(200)
            ->assertJsonPath('data.slug', 'peluncuran-layanan-digital-terpadu');
    }

    public function test_can_fetch_categories(): void
    {
        $response = $this->getJson('/api/v1/categories');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name', 'slug'],
                ],
            ]);
    }

    public function test_can_fetch_static_page(): void
    {
        $response = $this->getJson('/api/v1/pages/profil-instansi');

        $response->assertStatus(200)
            ->assertJsonPath('data.slug', 'profil-instansi');
    }

    public function test_can_fetch_slides(): void
    {
        $response = $this->getJson('/api/v1/slides');

        $response->assertStatus(200)
            ->assertJsonStructure(['data']);
    }

    public function test_can_fetch_menu(): void
    {
        $response = $this->getJson('/api/v1/menus/header');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => ['id', 'name', 'location', 'items'],
            ]);
    }

    public function test_can_fetch_settings(): void
    {
        $response = $this->getJson('/api/v1/settings');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['group', 'key', 'value', 'type'],
                ],
            ]);
    }

    public function test_can_fetch_question_topics(): void
    {
        $response = $this->getJson('/api/v1/question-topics');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name', 'slug'],
                ],
            ]);
    }

    public function test_can_fetch_answered_questions(): void
    {
        $response = $this->getJson('/api/v1/questions');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'subject', 'body', 'status', 'answers'],
                ],
            ]);
    }

    public function test_can_submit_new_question_from_public(): void
    {
        $topic = QuestionTopic::first();

        $payload = [
            'topic_id' => $topic?->id,
            'name' => 'Ahmad Fadli',
            'email' => 'fadli@example.com',
            'phone' => '08987654321',
            'subject' => 'Pertanyaan mengenai persyaratan pendaftaran',
            'body' => 'Mohon informasi mengenai berkas apa saja yang wajib dibawa saat verifikasi fisik.',
        ];

        $response = $this->postJson('/api/v1/questions', $payload);

        $response->assertStatus(201)
            ->assertJsonPath('data.subject', $payload['subject']);

        $this->assertDatabaseHas('questions', [
            'email' => 'fadli@example.com',
            'subject' => $payload['subject'],
        ]);
    }

    public function test_can_fetch_faqs(): void
    {
        $response = $this->getJson('/api/v1/faqs');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'question', 'answer'],
                ],
            ]);
    }

    public function test_notification_sent_when_answer_is_published(): void
    {
        Notification::fake();

        $user = User::first();
        $topic = QuestionTopic::first();

        $question = Question::create([
            'topic_id' => $topic?->id,
            'name' => 'Siti Nurhaliza',
            'email' => 'siti@example.com',
            'phone' => '0811223344',
            'subject' => 'Pertanyaan jam buka layanan kependudukan',
            'body' => 'Apakah hari libur nasional loket layanan tetap buka?',
            'status' => QuestionStatus::Pending,
        ]);

        QuestionAnswer::create([
            'question_id' => $question->id,
            'user_id' => $user->id,
            'body' => '<p>Hari libur nasional loket layanan tutup.</p>',
            'status' => AnswerStatus::Published,
            'is_published' => true,
            'answered_at' => now(),
        ]);

        $this->assertEquals(QuestionStatus::Answered, $question->fresh()->status);

        Notification::assertSentOnDemand(
            QuestionAnsweredNotification::class,
            function ($notification, $channels, $notifiable) {
                return $notifiable->routes['mail'] === 'siti@example.com';
            }
        );
    }
}
