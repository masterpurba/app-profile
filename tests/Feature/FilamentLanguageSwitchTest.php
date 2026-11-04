<?php

namespace Tests\Feature;

use App\Models\User;
use BezhanSalleh\LanguageSwitch\Http\Livewire\LanguageSwitchComponent;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class FilamentLanguageSwitchTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_filament_language_switch_renders_configured_languages(): void
    {
        Livewire::test(LanguageSwitchComponent::class)
            ->assertSee('Bahasa Indonesia')
            ->assertSee('English');
    }

    public function test_filament_language_switch_can_change_locale(): void
    {
        Livewire::test(LanguageSwitchComponent::class)
            ->call('changeLocale', 'en');

        $this->assertEquals('en', session('locale'));
    }

    public function test_admin_panel_displays_language_switch(): void
    {
        $admin = User::first();

        $this->actingAs($admin)
            ->get('/admin')
            ->assertSuccessful()
            ->assertSeeLivewire(LanguageSwitchComponent::class);
    }
}

