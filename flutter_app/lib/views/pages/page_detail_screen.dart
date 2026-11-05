import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../../l10n/app_localizations.dart';
import '../../models/page_model.dart';
import '../../providers/locale_provider.dart';
import '../../services/api_service.dart';
import '../../widgets/error_state.dart';
import '../../widgets/loading_indicator.dart';

class PageDetailScreen extends StatefulWidget {
  final String slug;

  const PageDetailScreen({super.key, required this.slug});

  @override
  State<PageDetailScreen> createState() => _PageDetailScreenState();
}

class _PageDetailScreenState extends State<PageDetailScreen> {
  PageModel? _page;
  bool _isLoading = true;
  String? _errorMessage;

  @override
  void initState() {
    super.initState();
    _fetchPage();
  }

  Future<void> _fetchPage() async {
    setState(() {
      _isLoading = true;
      _errorMessage = null;
    });

    final lang = context.read<LocaleProvider>().locale.languageCode;
    final api = ApiService(languageCode: lang);
    final res = await api.getPageDetail(widget.slug);

    if (mounted) {
      setState(() {
        _isLoading = false;
        if (res.isSuccess) {
          _page = res.data;
        } else {
          _errorMessage = res.message ?? 'Page not found';
        }
      });
    }
  }

  String _stripHtml(String htmlString) {
    final exp = RegExp(r'<[^>]*>', multiLine: true, caseSensitive: false);
    return htmlString.replaceAll(exp, '').replaceAll('&nbsp;', ' ').trim();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text(_page?.title ?? context.tr('profile_page')),
      ),
      body: _isLoading
          ? const LoadingIndicator()
          : _errorMessage != null
              ? ErrorStateWidget(
                  message: _errorMessage,
                  onRetry: _fetchPage,
                )
              : _page == null
                  ? const SizedBox.shrink()
                  : SingleChildScrollView(
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          if (_page!.imageUrl != null &&
                              _page!.imageUrl!.isNotEmpty)
                            SizedBox(
                              height: 200,
                              width: double.infinity,
                              child: Image.network(
                                _page!.imageUrl!,
                                fit: BoxFit.cover,
                                errorBuilder: (context, error, stackTrace) => const SizedBox.shrink(),
                              ),
                            ),
                          Padding(
                            padding: const EdgeInsets.all(20),
                            child: Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                Text(
                                  _page!.title,
                                  style: const TextStyle(
                                    fontSize: 22,
                                    fontWeight: FontWeight.bold,
                                    color: Color(0xFF0F172A),
                                  ),
                                ),
                                const SizedBox(height: 16),
                                const Divider(color: Color(0xFFE2E8F0)),
                                const SizedBox(height: 16),
                                Text(
                                  _stripHtml(_page!.content),
                                  style: const TextStyle(
                                    fontSize: 15,
                                    color: Color(0xFF334155),
                                    height: 1.7,
                                  ),
                                ),
                                const SizedBox(height: 40),
                              ],
                            ),
                          ),
                        ],
                      ),
                    ),
    );
  }
}
