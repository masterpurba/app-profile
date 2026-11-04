<?php

namespace Tests\Feature;

use App\Filament\Resources\Categories\Pages\CreateCategory;
use App\Filament\Resources\Categories\Pages\EditCategory;
use App\Models\Category;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class FilamentRedirectTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_panel_has_index_redirect_configuration(): void
    {
        $panel = Filament::getPanel('admin');

        $this->assertEquals('index', $panel->getResourceCreatePageRedirect());
        $this->assertEquals('index', $panel->getResourceEditPageRedirect());
    }

    public function test_create_category_redirects_to_index(): void
    {
        $admin = User::first();
        $this->actingAs($admin);

        Livewire::test(CreateCategory::class)
            ->fillForm([
                'name' => 'Kategori Baru Test',
                'slug' => 'kategori-baru-test',
                'is_active' => true,
            ])
            ->call('create')
            ->assertHasNoFormErrors()
            ->assertRedirect(CreateCategory::getResource()::getUrl('index'));
    }

    public function test_edit_category_redirects_to_index(): void
    {
        $admin = User::first();
        $this->actingAs($admin);

        $category = Category::first();

        Livewire::test(EditCategory::class, ['record' => $category->id])
            ->fillForm([
                'name' => 'Kategori Terupdate Test',
            ])
            ->call('save')
            ->assertHasNoFormErrors()
            ->assertRedirect(EditCategory::getResource()::getUrl('index'));
    }
}
