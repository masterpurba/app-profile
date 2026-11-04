<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LocaleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_can_switch_locale_to_english(): void
    {
        $response = $this->get('/switch-locale/en');

        $response->assertRedirect();
        $response->assertSessionHas('locale', 'en');
    }

    public function test_can_switch_locale_to_indonesian(): void
    {
        $response = $this->withSession(['locale' => 'en'])->get('/switch-locale/id');

        $response->assertRedirect();
        $response->assertSessionHas('locale', 'id');
    }

    public function test_set_locale_middleware_applies_locale_from_session(): void
    {
        $admin = User::first();

        $this->actingAs($admin)
            ->withSession(['locale' => 'en'])
            ->get('/admin')
            ->assertStatus(200);

        $this->assertEquals('en', app()->getLocale());
    }

    public function test_api_accepts_language_header(): void
    {
        $this->withHeader('Accept-Language', 'en-US,en;q=0.9')
            ->getJson('/api/v1/posts')
            ->assertStatus(200);

        $this->assertEquals('en', app()->getLocale());
    }
}
