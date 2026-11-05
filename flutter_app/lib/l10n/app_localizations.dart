import 'package:flutter/material.dart';

class AppLocalizations {
  final Locale locale;

  AppLocalizations(this.locale);

  static AppLocalizations of(BuildContext context) {
    return Localizations.of<AppLocalizations>(context, AppLocalizations) ??
        AppLocalizations(const Locale('id'));
  }

  static const LocalizationsDelegate<AppLocalizations> delegate =
      _AppLocalizationsDelegate();

  static final Map<String, Map<String, String>> _localizedValues = {
    'id': {
      'app_title': 'Portal Layanan Instansi',
      'nav_home': 'Beranda',
      'nav_news': 'Berita',
      'nav_questions': 'Tanya Kami',
      'nav_faqs': 'FAQ',
      'nav_settings': 'Pengaturan',

      // Home
      'welcome_title': 'Portal Resmi Informasi & Aspirasi',
      'welcome_subtitle': 'Akses informasi publik dan sampaikan aspirasi Anda secara mudah',
      'quick_menu': 'Menu Cepat',
      'featured_news': 'Berita Utama',
      'latest_news': 'Berita & Pengumuman Terbaru',
      'view_all': 'Lihat Semua',
      'ask_question_cta': 'Punya Pertanyaan atau Pengaduan?',
      'ask_question_desc': 'Kirimkan pertanyaan Anda langsung kepada petugas kami dan dapatkan jawaban resmi.',
      'ask_now': 'Ajukan Pertanyaan',

      // Posts
      'search_posts_hint': 'Cari berita, artikel, atau pengumuman...',
      'all_categories': 'Semua Kategori',
      'views': 'Dilihat',
      'published_on': 'Diterbitkan pada',
      'by': 'oleh',
      'no_posts_found': 'Tidak ada berita yang ditemukan.',

      // Questions
      'tab_public_questions': 'Pertanyaan Terjawab',
      'tab_ask_question': 'Ajukan Pertanyaan',
      'search_questions_hint': 'Cari pertanyaan masyarakat...',
      'filter_topic': 'Filter Topik',
      'all_topics': 'Semua Topik',
      'no_questions_found': 'Belum ada pertanyaan publik yang terjawab.',
      'official_answer': 'Jawaban Resmi Instansi',
      'answered_at': 'Dijawab pada',
      'status_answered': 'Terjawab',

      // Submit Question Form
      'form_topic_label': 'Topik Layanan *',
      'form_topic_hint': 'Pilih kategori topik pertanyaan',
      'form_name_label': 'Nama Lengkap *',
      'form_name_hint': 'Masukkan nama sesuai identitas',
      'form_email_label': 'Alamat Email *',
      'form_email_hint': 'Untuk menerima notifikasi jawaban',
      'form_phone_label': 'Nomor Telepon / WhatsApp (Opsional)',
      'form_phone_hint': 'Contoh: 081234567890',
      'form_subject_label': 'Subjek Pertanyaan *',
      'form_subject_hint': 'Ringkasan singkat pertanyaan Anda',
      'form_body_label': 'Isi Pertanyaan Lengkap *',
      'form_body_hint': 'Tuliskan detail pertanyaan atau pengaduan secara jelas...',
      'btn_submit_question': 'Kirimkan Pertanyaan',
      'submit_success_title': 'Pertanyaan Berhasil Dikirim!',
      'submit_success_desc': 'Terima kasih, pertanyaan Anda telah kami terima dan akan segera ditindaklanjuti. Notifikasi jawaban akan dikirimkan ke email Anda.',
      'btn_back_to_home': 'Kembali ke Beranda',

      // FAQs
      'faq_title': 'Tanya Jawab Umum (FAQ)',
      'faq_subtitle': 'Pertanyaan yang paling sering diajukan masyarakat seputar layanan instansi.',
      'search_faqs_hint': 'Cari jawaban cepat...',
      'no_faqs_found': 'Tidak ada FAQ yang cocok dengan filter.',

      // Settings
      'language_setting': 'Bahasa Aplikasi',
      'language_desc': 'Ubah bahasa antarmuka dan konten aplikasi',
      'server_setting': 'Konfigurasi Server API',
      'server_desc': 'Alamat backend Laravel yang diakses',
      'agency_info': 'Informasi Kontak Instansi',
      'contact_email': 'Email Resmi',
      'contact_phone': 'Nomor Telepon',
      'contact_address': 'Alamat Kantor',
      'profile_page': 'Profil Instansi',
      'app_version': 'Versi Aplikasi',

      // Common
      'loading': 'Memuat data...',
      'error_title': 'Terjadi Kendala',
      'error_subtitle': 'Gagal terhubung ke server atau memuat data.',
      'retry': 'Coba Lagi',
      'required_field': 'Kolom ini wajib diisi',
      'invalid_email': 'Format email tidak valid',
    },
    'en': {
      'app_title': 'Public Agency Portal',
      'nav_home': 'Home',
      'nav_news': 'News',
      'nav_questions': 'Ask Us',
      'nav_faqs': 'FAQ',
      'nav_settings': 'Settings',

      // Home
      'welcome_title': 'Official Information & Inquiries Portal',
      'welcome_subtitle': 'Access public information and submit your inquiries with ease',
      'quick_menu': 'Quick Access',
      'featured_news': 'Featured Story',
      'latest_news': 'Latest News & Announcements',
      'view_all': 'View All',
      'ask_question_cta': 'Have a Question or Inquiry?',
      'ask_question_desc': 'Submit your inquiry directly to our officers and receive official answers.',
      'ask_now': 'Submit an Inquiry',

      // Posts
      'search_posts_hint': 'Search news, articles, or announcements...',
      'all_categories': 'All Categories',
      'views': 'Views',
      'published_on': 'Published on',
      'by': 'by',
      'no_posts_found': 'No articles found.',

      // Questions
      'tab_public_questions': 'Answered Inquiries',
      'tab_ask_question': 'Submit Inquiry',
      'search_questions_hint': 'Search community inquiries...',
      'filter_topic': 'Filter Topic',
      'all_topics': 'All Topics',
      'no_questions_found': 'No answered inquiries found yet.',
      'official_answer': 'Official Agency Response',
      'answered_at': 'Answered on',
      'status_answered': 'Answered',

      // Submit Question Form
      'form_topic_label': 'Service Topic *',
      'form_topic_hint': 'Select topic category',
      'form_name_label': 'Full Name *',
      'form_name_hint': 'Enter name as on ID',
      'form_email_label': 'Email Address *',
      'form_email_hint': 'To receive response notification',
      'form_phone_label': 'Phone / WhatsApp (Optional)',
      'form_phone_hint': 'Example: +62 812 3456 7890',
      'form_subject_label': 'Inquiry Subject *',
      'form_subject_hint': 'Brief summary of your question',
      'form_body_label': 'Detailed Inquiry *',
      'form_body_hint': 'Provide full details of your question or feedback...',
      'btn_submit_question': 'Send Inquiry',
      'submit_success_title': 'Inquiry Sent Successfully!',
      'submit_success_desc': 'Thank you! Your inquiry has been submitted and will be reviewed by our team. You will be notified via email.',
      'btn_back_to_home': 'Back to Home',

      // FAQs
      'faq_title': 'Frequently Asked Questions (FAQ)',
      'faq_subtitle': 'Common inquiries from citizens regarding our services and procedures.',
      'search_faqs_hint': 'Search for quick answers...',
      'no_faqs_found': 'No FAQs match the current filter.',

      // Settings
      'language_setting': 'App Language',
      'language_desc': 'Change UI and content language',
      'server_setting': 'API Server Configuration',
      'server_desc': 'Backend Laravel URL endpoint',
      'agency_info': 'Official Contact Info',
      'contact_email': 'Official Email',
      'contact_phone': 'Phone Number',
      'contact_address': 'Office Address',
      'profile_page': 'Agency Profile',
      'app_version': 'App Version',

      // Common
      'loading': 'Loading data...',
      'error_title': 'Connection Error',
      'error_subtitle': 'Unable to connect to the server or load data.',
      'retry': 'Retry',
      'required_field': 'This field is required',
      'invalid_email': 'Please enter a valid email address',
    },
  };

  String translate(String key) {
    return _localizedValues[locale.languageCode]?[key] ??
        _localizedValues['id']?[key] ??
        key;
  }
}

class _AppLocalizationsDelegate
    extends LocalizationsDelegate<AppLocalizations> {
  const _AppLocalizationsDelegate();

  @override
  bool isSupported(Locale locale) => ['id', 'en'].contains(locale.languageCode);

  @override
  Future<AppLocalizations> load(Locale locale) async {
    return AppLocalizations(locale);
  }

  @override
  bool shouldReload(_AppLocalizationsDelegate old) => false;
}

extension AppLocalizationsX on BuildContext {
  AppLocalizations get l10n => AppLocalizations.of(this);
  String tr(String key) => AppLocalizations.of(this).translate(key);
}

