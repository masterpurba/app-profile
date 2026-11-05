# 📱 Portal Layanan Instansi — Flutter Frontend

Aplikasi frontend publik (mobile & web) untuk portal informasi instansi, berita, dan layanan pengaduan / pertanyaan masyarakat. Aplikasi ini dibangun menggunakan **Flutter 3.x**, terintegrasi langsung dengan backend **Laravel 13 REST API** (`/api/v1/*`), serta mendukung navigasi modern **[go_router](https://pub.dev/packages/go_router)** dan **Multibahasa (Indonesian & English)**.

---

## 🚀 Fitur Utama

1. **Routing Modern dengan `go_router`**:
   - `StatefulShellRoute.indexedStack` menjaga status halaman (persistent navigation bar).
   - Rute:
     - `/` — **Beranda / Home**: Slider banner promosi, menu akses cepat, berita utama & terbaru, CTA pertanyaan.
     - `/posts` — **Berita / News**: Pencarian artikel, filter kategori, daftar berita.
     - `/posts/:slug` — **Detail Berita**: Foto unggulan, kategori, counter pembaca, konten lengkap.
     - `/pages/:slug` — **Halaman Statis**: Profil Instansi, Visi Misi, dll.
     - `/questions` — **Tanya Kami**:
       - *Tab 1*: Daftar pertanyaan masyarakat yang telah dijawab resmi oleh admin.
       - *Tab 2*: Formulir pengiriman pertanyaan baru (pilihan topik, validasi form, rate-limiting).
     - `/faqs` — **FAQ**: Tanya jawab umum yang dapat diperluas (*expandable*) per topik layanan.
     - `/settings` — **Pengaturan**: Pengubah bahasa aplikasi & info kontak instansi.

2. **Dukungan Penuh Multibahasa (ID & EN)**:
   - **Tingkat Klien (UI)**: Label menu, tombol, form, pesan error/sukses otomatis berganti saat bahasa dipilih.
   - **Tingkat Server (Data API)**: Setiap request HTTP menyertakan header `Accept-Language: id` atau `Accept-Language: en`. Backend Laravel (Spatie Translatable) secara otomatis merespons dengan konten berita, kategori, topik, slide, dan FAQ dalam bahasa yang sesuai.
   - Pilihan bahasa tersimpan otomatis di perangkat (`SharedPreferences`).

3. **Konfigurasi Fleksibel Base URL**:
   - Secara default mendeteksi platform:
     - Android Emulator: `http://10.0.2.2:8000/api/v1`
     - Web / Desktop / Device: `http://127.0.0.1:8000/api/v1`
   - Tersedia dialog pengaturan di halaman **Pengaturan** untuk mengganti alamat IP server langsung dari aplikasi tanpa perlu compile ulang.

---

## 📂 Struktur Proyek

```
flutter_app/lib/
├── config/
│   ├── app_config.dart          # Base URL & preferensi koneksi
│   ├── app_theme.dart           # Tema Material 3 modern (Indigo & Slate)
│   └── routes.dart              # Konfigurasi GoRouter & rute navigasi
├── l10n/
│   └── app_localizations.dart   # Kamus lokalisasi UI (ID & EN)
├── models/
│   ├── category.dart            # Model Kategori
│   ├── post.dart                # Model Artikel / Berita
│   ├── page_model.dart          # Model Halaman Statis
│   ├── slide.dart               # Model Slide Banner Beranda
│   ├── menu_item_model.dart     # Model Menu Navigasi
│   ├── question.dart            # Model Pertanyaan, Topik & Jawaban
│   ├── faq.dart                 # Model Tanya Jawab (FAQ)
│   └── site_setting.dart        # Model Pengaturan Situs
├── providers/
│   ├── locale_provider.dart     # State manajemen bahasa (ID / EN)
│   ├── home_provider.dart       # State data beranda (slides, berita utama)
│   ├── post_provider.dart       # State berita & filter kategori
│   ├── question_provider.dart   # State tanya jawab publik & kirim form
│   └── faq_provider.dart        # State FAQ per topik
├── services/
│   └── api_service.dart         # HTTP Client ke REST API Laravel v1
├── views/
│   ├── shell_view.dart          # Bottom Navigation Bar
│   ├── home/home_screen.dart    # Tampilan Beranda
│   ├── posts/
│   │   ├── post_list_screen.dart
│   │   └── post_detail_screen.dart
│   ├── pages/page_detail_screen.dart
│   ├── questions/questions_screen.dart
│   ├── faqs/faqs_screen.dart
│   └── settings/settings_screen.dart
├── widgets/
│   ├── banner_carousel.dart     # Carousel slide banner
│   ├── post_card.dart           # Komponen kartu artikel
│   ├── error_state.dart         # Widget kendala koneksi & retry
│   └── loading_indicator.dart   # Widget loading
└── main.dart                    # Entry point aplikasi
```

---

## 🏃 Cara Menjalankan Aplikasi

1. **Jalankan Backend Laravel Terlebih Dahulu**:
   Di terminal root proyek (`c:\laragon\www\app-profile`):
   ```bash
   php artisan serve
   ```
   *(Server berjalan di `http://127.0.0.1:8000`)*

2. **Jalankan Aplikasi Flutter**:
   Pindah ke direktori `flutter_app`:
   ```bash
   cd flutter_app
   ```

   - **Menjalankan di Web (Chrome)**:
     ```bash
     flutter run -d chrome
     ```
   - **Menjalankan di Windows Desktop**:
     ```bash
     flutter run -d windows
     ```
   - **Menjalankan di Emulator Android / Perangkat Fisik**:
     ```bash
     flutter run
     ```
