import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../../l10n/app_localizations.dart';
import '../../models/post.dart';
import '../../providers/locale_provider.dart';
import '../../services/api_service.dart';
import '../../widgets/error_state.dart';
import '../../widgets/loading_indicator.dart';

class PostDetailScreen extends StatefulWidget {
  final String slug;

  const PostDetailScreen({super.key, required this.slug});

  @override
  State<PostDetailScreen> createState() => _PostDetailScreenState();
}

class _PostDetailScreenState extends State<PostDetailScreen> {
  Post? _post;
  bool _isLoading = true;
  String? _errorMessage;

  @override
  void initState() {
    super.initState();
    _fetchDetail();
  }

  Future<void> _fetchDetail() async {
    setState(() {
      _isLoading = true;
      _errorMessage = null;
    });

    final lang = context.read<LocaleProvider>().locale.languageCode;
    final api = ApiService(languageCode: lang);
    final res = await api.getPostDetail(widget.slug);

    if (mounted) {
      setState(() {
        _isLoading = false;
        if (res.isSuccess) {
          _post = res.data;
        } else {
          _errorMessage = res.message ?? 'Not found';
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
        title: Text(_post?.title ?? context.tr('nav_news')),
      ),
      body: _isLoading
          ? const LoadingIndicator()
          : _errorMessage != null
              ? ErrorStateWidget(
                  message: _errorMessage,
                  onRetry: _fetchDetail,
                )
              : _post == null
                  ? const SizedBox.shrink()
                  : SingleChildScrollView(
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          // Header Image
                          if (_post!.imageUrl != null &&
                              _post!.imageUrl!.isNotEmpty)
                            SizedBox(
                              height: 220,
                              width: double.infinity,
                              child: Image.network(
                                _post!.imageUrl!,
                                fit: BoxFit.cover,
                                errorBuilder: (context, error, stackTrace) => const SizedBox.shrink(),
                              ),
                            ),

                          Padding(
                            padding: const EdgeInsets.all(20),
                            child: Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                // Category Tag
                                if (_post!.category != null)
                                  Container(
                                    padding: const EdgeInsets.symmetric(
                                      horizontal: 10,
                                      vertical: 4,
                                    ),
                                    decoration: BoxDecoration(
                                      color: const Color(0xFFEEF2FF),
                                      borderRadius: BorderRadius.circular(8),
                                    ),
                                    child: Text(
                                      _post!.category!.name,
                                      style: const TextStyle(
                                        color: Color(0xFF4F46E5),
                                        fontSize: 12,
                                        fontWeight: FontWeight.w700,
                                      ),
                                    ),
                                  ),

                                const SizedBox(height: 12),

                                // Title
                                Text(
                                  _post!.title,
                                  style: const TextStyle(
                                    fontSize: 22,
                                    fontWeight: FontWeight.bold,
                                    color: Color(0xFF0F172A),
                                    height: 1.3,
                                  ),
                                ),

                                const SizedBox(height: 14),

                                // Author & Metadata Bar
                                Row(
                                  children: [
                                    CircleAvatar(
                                      radius: 18,
                                      backgroundColor: const Color(0xFFE0E7FF),
                                      child: Text(
                                        _post!.author.name.isNotEmpty
                                            ? _post!.author.name[0].toUpperCase()
                                            : 'A',
                                        style: const TextStyle(
                                          color: Color(0xFF4F46E5),
                                          fontWeight: FontWeight.bold,
                                        ),
                                      ),
                                    ),
                                    const SizedBox(width: 10),
                                    Expanded(
                                      child: Column(
                                        crossAxisAlignment: CrossAxisAlignment.start,
                                        children: [
                                          Text(
                                            _post!.author.name,
                                            style: const TextStyle(
                                              fontSize: 13,
                                              fontWeight: FontWeight.w600,
                                              color: Color(0xFF1E293B),
                                            ),
                                          ),
                                          if (_post!.publishedAt != null)
                                            Text(
                                              '${context.tr('published_on')} ${_post!.publishedAt!.split('T').first}',
                                              style: const TextStyle(
                                                fontSize: 11,
                                                color: Color(0xFF64748B),
                                              ),
                                            ),
                                        ],
                                      ),
                                    ),
                                    Container(
                                      padding: const EdgeInsets.symmetric(
                                        horizontal: 8,
                                        vertical: 4,
                                      ),
                                      decoration: BoxDecoration(
                                        color: const Color(0xFFF1F5F9),
                                        borderRadius: BorderRadius.circular(6),
                                      ),
                                      child: Row(
                                        children: [
                                          const Icon(
                                            Icons.remove_red_eye_outlined,
                                            size: 14,
                                            color: Color(0xFF64748B),
                                          ),
                                          const SizedBox(width: 4),
                                          Text(
                                            '${_post!.viewCount}',
                                            style: const TextStyle(
                                              fontSize: 11,
                                              fontWeight: FontWeight.w600,
                                              color: Color(0xFF64748B),
                                            ),
                                          ),
                                        ],
                                      ),
                                    ),
                                  ],
                                ),

                                const SizedBox(height: 20),
                                const Divider(color: Color(0xFFE2E8F0)),
                                const SizedBox(height: 16),

                                // Summary Callout
                                if (_post!.summary != null &&
                                    _post!.summary!.isNotEmpty) ...[
                                  Container(
                                    padding: const EdgeInsets.all(14),
                                    decoration: BoxDecoration(
                                      color: const Color(0xFFF8FAFC),
                                      borderRadius: BorderRadius.circular(12),
                                      border: const Border(
                                        left: BorderSide(
                                          color: Color(0xFF4F46E5),
                                          width: 4,
                                        ),
                                      ),
                                    ),
                                    child: Text(
                                      _post!.summary!,
                                      style: const TextStyle(
                                        fontSize: 14,
                                        fontStyle: FontStyle.italic,
                                        color: Color(0xFF334155),
                                        height: 1.5,
                                      ),
                                    ),
                                  ),
                                  const SizedBox(height: 20),
                                ],

                                // Content
                                if (_post!.content != null)
                                  Text(
                                    _stripHtml(_post!.content!),
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
