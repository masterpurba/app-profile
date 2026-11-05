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
            'name' => [
                'id' => 'Berita',
                'en' => 'News',
            ],
            'description' => [
                'id' => 'Berita dan peristiwa terkini',
                'en' => 'Latest news and current events',
            ],
            'icon' => 'newspaper',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $pengumuman = Category::firstOrCreate(['slug' => 'pengumuman'], [
            'name' => [
                'id' => 'Pengumuman',
                'en' => 'Announcements',
            ],
            'description' => [
                'id' => 'Pengumuman resmi kedinasan',
                'en' => 'Official agency announcements',
            ],
            'icon' => 'megaphone',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        // 4. Sample Post
        Post::firstOrCreate(['slug' => 'peluncuran-layanan-digital-terpadu'], [
            'user_id' => $admin->id,
            'category_id' => $berita->id,
            'title' => [
                'id' => 'Peluncuran Layanan Digital Terpadu untuk Masyarakat',
                'en' => 'Launch of Integrated Digital Services for the Public',
            ],
            'summary' => [
                'id' => 'Inovasi transformasi digital untuk mempercepat respon pertanyaan dan layanan masyarakat.',
                'en' => 'Digital transformation innovation to accelerate responses to public inquiries and services.',
            ],
            'content' => [
                'id' => '<p>Instansi resmi meluncurkan portal layanan terpadu berbasis digital untuk mempermudah akses informasi publik dan pengajuan aspirasi.</p>',
                'en' => '<p>The agency officially launches an integrated digital service portal to facilitate public information access and aspirations submission.</p>',
            ],
            'status' => PostStatus::Published,
            'published_at' => now(),
            'view_count' => 150,
            'is_featured' => true,
        ]);

        // 5. Sample Static Page
        Page::firstOrCreate(['slug' => 'profil-instansi'], [
            'user_id' => $admin->id,
            'title' => [
                'id' => 'Profil Instansi',
                'en' => 'Agency Profile',
            ],
            'content' => [
                'id' => '<p>Portal ini bertujuan memberikan pelayanan prima dan transparansi informasi publik kepada seluruh lapisan masyarakat.</p>',
                'en' => '<p>This portal aims to provide excellent service and public information transparency to all layers of society.</p>',
            ],
            'status' => PageStatus::Published,
            'sort_order' => 1,
            'show_in_menu' => true,
        ]);

        // 6. Question Topics
        $topicPelayanan = QuestionTopic::firstOrCreate(['slug' => 'pelayanan-publik'], [
            'name' => [
                'id' => 'Pelayanan Publik',
                'en' => 'Public Services',
            ],
            'description' => [
                'id' => 'Pertanyaan terkait jam operasional dan prosedur layanan',
                'en' => 'Questions related to operating hours and service procedures',
            ],
            'icon' => 'user-group',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $topicAdministrasi = QuestionTopic::firstOrCreate(['slug' => 'administrasi'], [
            'name' => [
                'id' => 'Administrasi & Perizinan',
                'en' => 'Administration & Licensing',
            ],
            'description' => [
                'id' => 'Konsultasi berkas dan alur permohonan',
                'en' => 'Document consultation and application workflows',
            ],
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
        Faq::firstOrCreate(['sort_order' => 1], [
            'topic_id' => $topicAdministrasi->id,
            'question' => [
                'id' => 'Bagaimana cara mengajukan permohonan perizinan secara daring?',
                'en' => 'How to submit an online permit application?',
            ],
            'answer' => [
                'id' => 'Anda dapat mendaftar melalui menu Layanan Publik, melengkapi formulir digital dan mengunggah dokumen persyaratan.',
                'en' => 'You can register via the Public Services menu, complete the digital form and upload required documents.',
            ],
            'sort_order' => 1,
            'is_active' => true,
        ]);

        // 9. Sample Slide
        Slide::firstOrCreate(['sort_order' => 1], [
            'title' => [
                'id' => 'Selamat Datang di Portal Resmi',
                'en' => 'Welcome to the Official Portal',
            ],
            'description' => [
                'id' => 'Pusat informasi berita dan penyampaian aspirasi masyarakat.',
                'en' => 'Center for news information and public aspiration submissions.',
            ],
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

        MenuItem::firstOrCreate(['menu_id' => $menuHeader->id, 'sort_order' => 1], [
            'label' => [
                'id' => 'Beranda',
                'en' => 'Home',
            ],
            'url' => '/',
            'type' => MenuItemType::Custom,
            'sort_order' => 1,
            'is_active' => true,
        ]);

        MenuItem::firstOrCreate(['menu_id' => $menuHeader->id, 'sort_order' => 2], [
            'label' => [
                'id' => 'Profil Instansi',
                'en' => 'Agency Profile',
            ],
            'url' => '/pages/profil-instansi',
            'type' => MenuItemType::Page,
            'sort_order' => 2,
            'is_active' => true,
        ]);

        MenuItem::firstOrCreate(['menu_id' => $menuHeader->id, 'sort_order' => 3], [
            'label' => [
                'id' => 'Tanya Kami',
                'en' => 'Ask Us',
            ],
            'url' => '/pertanyaan',
            'type' => MenuItemType::Custom,
            'sort_order' => 3,
            'is_active' => true,
        ]);
    }
}
