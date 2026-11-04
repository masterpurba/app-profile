<?php

namespace Database\Seeders;

use App\Enums\AnswerStatus;
use App\Enums\MenuItemType;
use App\Enums\MenuLocation;
use App\Enums\PageStatus;
use App\Enums\PostStatus;
use App\Enums\QuestionStatus;
use App\Enums\SettingType;
use App\Enums\UserRole;
use App\Models\Category;
use App\Models\Faq;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\Page;
use App\Models\Post;
use App\Models\Question;
use App\Models\QuestionAnswer;
use App\Models\QuestionTopic;
use App\Models\Setting;
use App\Models\Slide;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'),
                'role' => UserRole::Admin,
            ]
        );

        // 2. Settings
        $settings = [
            ['group' => 'general', 'key' => 'site_name', 'value' => 'Portal Informasi & Pengaduan Instansi', 'type' => SettingType::Text],
            ['group' => 'general', 'key' => 'site_description', 'value' => 'Sistem Informasi CMS dan Layanan Pertanyaan Masyarakat', 'type' => SettingType::Textarea],
            ['group' => 'contact', 'key' => 'contact_email', 'value' => 'info@instansi.go.id', 'type' => SettingType::Text],
            ['group' => 'contact', 'key' => 'contact_phone', 'value' => '(021) 12345678', 'type' => SettingType::Text],
            ['group' => 'contact', 'key' => 'contact_address', 'value' => 'Jl. Merdeka No. 45, Jakarta Pusat', 'type' => SettingType::Textarea],
        ];

        foreach ($settings as $setting) {
            Setting::firstOrCreate(['key' => $setting['key']], $setting);
        }

        // 3. Categories
        $berita = Category::firstOrCreate(['slug' => 'berita'], [
            'name' => 'Berita',
            'description' => 'Berita dan peristiwa terkini',
            'icon' => 'newspaper',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $pengumuman = Category::firstOrCreate(['slug' => 'pengumuman'], [
            'name' => 'Pengumuman',
            'description' => 'Pengumuman resmi kedinasan',
            'icon' => 'megaphone',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        // 4. Sample Post
        Post::firstOrCreate(['slug' => 'peluncuran-layanan-digital-terpadu'], [
            'user_id' => $admin->id,
            'category_id' => $berita->id,
            'title' => 'Peluncuran Layanan Digital Terpadu untuk Masyarakat',
            'summary' => 'Inovasi transformasi digital untuk mempercepat respon pertanyaan dan layanan masyarakat.',
            'content' => '<p>Instansi resmi meluncurkan portal layanan terpadu berbasis digital untuk mempermudah akses informasi publik dan pengajuan aspirasi.</p>',
            'status' => PostStatus::Published,
            'published_at' => now(),
            'view_count' => 150,
            'is_featured' => true,
        ]);

        // 5. Sample Static Page
        Page::firstOrCreate(['slug' => 'profil-instansi'], [
            'user_id' => $admin->id,
            'title' => 'Profil Instansi',
            'content' => '<p>Portal ini bertujuan memberikan pelayanan prima dan transparansi informasi publik kepada seluruh lapisan masyarakat.</p>',
            'status' => PageStatus::Published,
            'sort_order' => 1,
            'show_in_menu' => true,
        ]);

        // 6. Question Topics
        $topicPelayanan = QuestionTopic::firstOrCreate(['slug' => 'pelayanan-publik'], [
            'name' => 'Pelayanan Publik',
            'description' => 'Pertanyaan terkait jam operasional dan prosedur layanan',
            'icon' => 'user-group',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $topicAdministrasi = QuestionTopic::firstOrCreate(['slug' => 'administrasi'], [
            'name' => 'Administrasi & Perizinan',
            'description' => 'Konsultasi berkas dan alur permohonan',
            'icon' => 'document-text',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        // 7. Sample Question & Answer
        $question = Question::firstOrCreate(['email' => 'warga@gmail.com', 'subject' => 'Jam operasional layanan tatap muka'], [
            'topic_id' => $topicPelayanan->id,
            'name' => 'Budi Santoso',
            'phone' => '08123456789',
            'body' => 'Selamat pagi, apakah layanan loket administrasi tetap buka pada hari Sabtu?',
            'status' => QuestionStatus::Answered,
            'ip_address' => '127.0.0.1',
        ]);

        QuestionAnswer::firstOrCreate(['question_id' => $question->id], [
            'user_id' => $admin->id,
            'body' => 'Selamat pagi Bapak Budi. Layanan loket tatap muka beroperasi Senin-Jumat pukul 08.00 - 15.00 WIB. Hari Sabtu dan Minggu libur.',
            'status' => AnswerStatus::Published,
            'is_published' => true,
            'answered_at' => now(),
        ]);

        // 8. Sample FAQ
        Faq::firstOrCreate(['question' => 'Bagaimana cara mengajukan permohonan perizinan secara daring?'], [
            'topic_id' => $topicAdministrasi->id,
            'answer' => 'Anda dapat mendaftar melalui menu Layanan Publik, melengkapi formulir digital dan mengunggah dokumen persyaratan.',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        // 9. Sample Slide
        Slide::firstOrCreate(['title' => 'Selamat Datang di Portal Resmi'], [
            'description' => 'Pusat informasi berita dan penyampaian aspirasi masyarakat.',
            'image' => 'slides/default-slide.jpg',
            'link_url' => 'https://example.com',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        // 10. Sample Menu & Menu Items
        $menuHeader = Menu::firstOrCreate(['location' => MenuLocation::Header], [
            'name' => 'Menu Utama (Header)',
            'is_active' => true,
        ]);

        MenuItem::firstOrCreate(['menu_id' => $menuHeader->id, 'label' => 'Beranda'], [
            'url' => '/',
            'type' => MenuItemType::Custom,
            'sort_order' => 1,
            'is_active' => true,
        ]);

        MenuItem::firstOrCreate(['menu_id' => $menuHeader->id, 'label' => 'Profil Instansi'], [
            'url' => '/pages/profil-instansi',
            'type' => MenuItemType::Page,
            'sort_order' => 2,
            'is_active' => true,
        ]);

        MenuItem::firstOrCreate(['menu_id' => $menuHeader->id, 'label' => 'Tanya Kami'], [
            'url' => '/pertanyaan',
            'type' => MenuItemType::Custom,
            'sort_order' => 3,
            'is_active' => true,
        ]);
    }
}
