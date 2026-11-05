import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import 'package:provider/provider.dart';
import '../../config/app_config.dart';
import '../../l10n/app_localizations.dart';
import '../../providers/locale_provider.dart';
import '../../services/api_service.dart';

class SettingsScreen extends StatefulWidget {
  const SettingsScreen({super.key});

  @override
  State<SettingsScreen> createState() => _SettingsScreenState();
}

class _SettingsScreenState extends State<SettingsScreen> {
  Map<String, String> _settings = {};
  bool _isLoadingSettings = true;

  @override
  void initState() {
    super.initState();
    _loadSettings();
  }

  Future<void> _loadSettings() async {
    final lang = context.read<LocaleProvider>().locale.languageCode;
    final api = ApiService(languageCode: lang);
    final res = await api.getSettings();
    if (mounted && res.isSuccess) {
      setState(() {
        _settings = res.data ?? {};
        _isLoadingSettings = false;
      });
    }
  }

  void _showServerDialog() {
    final controller = TextEditingController(text: AppConfig.baseUrl);
    showDialog(
      context: context,
      builder: (ctx) => AlertDialog(
        title: Text(context.tr('server_setting')),
        content: Column(
          mainAxisSize: MainAxisSize.min,
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              context.tr('server_desc'),
              style: const TextStyle(fontSize: 12, color: Color(0xFF64748B)),
            ),
            const SizedBox(height: 12),
            TextField(
              controller: controller,
              decoration: const InputDecoration(
                hintText: 'http://127.0.0.1:8000/api/v1',
              ),
            ),
          ],
        ),
        actions: [
          TextButton(
            onPressed: () async {
              await AppConfig.resetBaseUrl();
              if (ctx.mounted) Navigator.pop(ctx);
              setState(() {});
              _loadSettings();
            },
            child: const Text('Reset Default'),
          ),
          FilledButton(
            onPressed: () async {
              final newUrl = controller.text.trim();
              if (newUrl.isNotEmpty) {
                await AppConfig.setBaseUrl(newUrl);
                if (ctx.mounted) Navigator.pop(ctx);
                setState(() {});
                _loadSettings();
              }
            },
            child: const Text('Simpan / Save'),
          ),
        ],
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    final localeProvider = context.watch<LocaleProvider>();

    return Scaffold(
      appBar: AppBar(
        title: Text(context.tr('nav_settings')),
      ),
      body: ListView(
        padding: const EdgeInsets.all(16),
        children: [
          // 1. Language Card
          _buildSectionHeader(context.tr('language_setting')),
          Card(
            shape: RoundedRectangleBorder(
              borderRadius: BorderRadius.circular(16),
              side: const BorderSide(color: Color(0xFFE2E8F0)),
            ),
            child: Column(
              children: [
                ListTile(
                  leading: const Text('🇮🇩', style: TextStyle(fontSize: 26)),
                  title: const Text(
                    'Bahasa Indonesia',
                    style: TextStyle(fontWeight: FontWeight.w600),
                  ),
                  subtitle: const Text('Bahasa Indonesia (Default)'),
                  trailing: localeProvider.isIndonesian
                      ? const Icon(Icons.check_circle_rounded, color: Color(0xFF4F46E5))
                      : const Icon(Icons.circle_outlined, color: Color(0xFFCBD5E1)),
                  onTap: () {
                    localeProvider.setLocale(const Locale('id'));
                    _loadSettings();
                  },
                ),
                const Divider(height: 1, indent: 64, color: Color(0xFFF1F5F9)),
                ListTile(
                  leading: const Text('🇬🇧', style: TextStyle(fontSize: 26)),
                  title: const Text(
                    'English',
                    style: TextStyle(fontWeight: FontWeight.w600),
                  ),
                  subtitle: const Text('English (Multilingual Content API)'),
                  trailing: !localeProvider.isIndonesian
                      ? const Icon(Icons.check_circle_rounded, color: Color(0xFF4F46E5))
                      : const Icon(Icons.circle_outlined, color: Color(0xFFCBD5E1)),
                  onTap: () {
                    localeProvider.setLocale(const Locale('en'));
                    _loadSettings();
                  },
                ),
              ],
            ),
          ),

          const SizedBox(height: 24),

          // 2. Server Configuration Card
          _buildSectionHeader(context.tr('server_setting')),
          Card(
            shape: RoundedRectangleBorder(
              borderRadius: BorderRadius.circular(16),
              side: const BorderSide(color: Color(0xFFE2E8F0)),
            ),
            child: ListTile(
              leading: Container(
                padding: const EdgeInsets.all(8),
                decoration: BoxDecoration(
                  color: const Color(0xFFEEF2FF),
                  borderRadius: BorderRadius.circular(10),
                ),
                child: const Icon(
                  Icons.dns_rounded,
                  color: Color(0xFF4F46E5),
                ),
              ),
              title: const Text(
                'REST API Endpoint',
                style: TextStyle(fontWeight: FontWeight.w600),
              ),
              subtitle: Text(
                AppConfig.baseUrl,
                style: const TextStyle(fontSize: 12, color: Color(0xFF64748B)),
              ),
              trailing: const Icon(Icons.edit_rounded, size: 20),
              onTap: _showServerDialog,
            ),
          ),

          const SizedBox(height: 24),

          // 3. Agency Information Card
          _buildSectionHeader(context.tr('agency_info')),
          Card(
            shape: RoundedRectangleBorder(
              borderRadius: BorderRadius.circular(16),
              side: const BorderSide(color: Color(0xFFE2E8F0)),
            ),
            child: _isLoadingSettings
                ? const Padding(
                    padding: EdgeInsets.all(20),
                    child: Center(child: CircularProgressIndicator()),
                  )
                : Column(
                    children: [
                      ListTile(
                        leading: const Icon(Icons.email_outlined, color: Color(0xFF4F46E5)),
                        title: Text(
                          context.tr('contact_email'),
                          style: const TextStyle(fontSize: 12, color: Color(0xFF64748B)),
                        ),
                        subtitle: Text(
                          _settings['contact_email'] ?? 'info@instansi.go.id',
                          style: const TextStyle(fontWeight: FontWeight.w600, color: Color(0xFF1E293B)),
                        ),
                      ),
                      const Divider(height: 1, indent: 56, color: Color(0xFFF1F5F9)),
                      ListTile(
                        leading: const Icon(Icons.phone_outlined, color: Color(0xFF4F46E5)),
                        title: Text(
                          context.tr('contact_phone'),
                          style: const TextStyle(fontSize: 12, color: Color(0xFF64748B)),
                        ),
                        subtitle: Text(
                          _settings['contact_phone'] ?? '(021) 12345678',
                          style: const TextStyle(fontWeight: FontWeight.w600, color: Color(0xFF1E293B)),
                        ),
                      ),
                      const Divider(height: 1, indent: 56, color: Color(0xFFF1F5F9)),
                      ListTile(
                        leading: const Icon(Icons.location_on_outlined, color: Color(0xFF4F46E5)),
                        title: Text(
                          context.tr('contact_address'),
                          style: const TextStyle(fontSize: 12, color: Color(0xFF64748B)),
                        ),
                        subtitle: Text(
                          _settings['contact_address'] ?? 'Jl. Merdeka No. 45, Jakarta Pusat',
                          style: const TextStyle(fontWeight: FontWeight.w600, color: Color(0xFF1E293B)),
                        ),
                      ),
                      const Divider(height: 1, indent: 56, color: Color(0xFFF1F5F9)),
                      ListTile(
                        leading: const Icon(Icons.info_outline_rounded, color: Color(0xFF4F46E5)),
                        title: Text(
                          context.tr('profile_page'),
                          style: const TextStyle(fontWeight: FontWeight.w600),
                        ),
                        trailing: const Icon(Icons.chevron_right_rounded),
                        onTap: () => context.push('/pages/profil-instansi'),
                      ),
                    ],
                  ),
          ),

          const SizedBox(height: 32),

          // App version badge
          Center(
            child: Column(
              children: [
                Text(
                  '${context.tr('app_title')} • v1.0.0',
                  style: const TextStyle(
                    fontSize: 12,
                    fontWeight: FontWeight.w600,
                    color: Color(0xFF94A3B8),
                  ),
                ),
                const SizedBox(height: 4),
                const Text(
                  'Laravel 13 API Backend + Flutter Frontend',
                  style: TextStyle(fontSize: 11, color: Color(0xFFCBD5E1)),
                ),
              ],
            ),
          ),

          const SizedBox(height: 30),
        ],
      ),
    );
  }

  Widget _buildSectionHeader(String title) {
    return Padding(
      padding: const EdgeInsets.only(left: 4, bottom: 8),
      child: Text(
        title,
        style: const TextStyle(
          fontSize: 14,
          fontWeight: FontWeight.bold,
          color: Color(0xFF475569),
        ),
      ),
    );
  }
}
