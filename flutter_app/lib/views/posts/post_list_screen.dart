import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import 'package:provider/provider.dart';
import '../../l10n/app_localizations.dart';
import '../../providers/locale_provider.dart';
import '../../providers/post_provider.dart';
import '../../widgets/error_state.dart';
import '../../widgets/loading_indicator.dart';
import '../../widgets/post_card.dart';

class PostListScreen extends StatefulWidget {
  const PostListScreen({super.key});

  @override
  State<PostListScreen> createState() => _PostListScreenState();
}

class _PostListScreenState extends State<PostListScreen> {
  final TextEditingController _searchController = TextEditingController();

  @override
  void initState() {
    super.initState();
    WidgetsBinding.instance.addPostFrameCallback((_) {
      _loadData();
    });
  }

  void _loadData() {
    final lang = context.read<LocaleProvider>().locale.languageCode;
    context.read<PostProvider>().init(lang);
  }

  @override
  void dispose() {
    _searchController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    final lang = context.watch<LocaleProvider>().locale.languageCode;
    final postProvider = context.watch<PostProvider>();

    return Scaffold(
      appBar: AppBar(
        title: Text(context.tr('nav_news')),
      ),
      body: Column(
        children: [
          // Search Bar
          Padding(
            padding: const EdgeInsets.fromLTRB(16, 8, 16, 8),
            child: TextField(
              controller: _searchController,
              decoration: InputDecoration(
                hintText: context.tr('search_posts_hint'),
                hintStyle: const TextStyle(fontSize: 13, color: Color(0xFF94A3B8)),
                prefixIcon: const Icon(Icons.search_rounded, color: Color(0xFF64748B)),
                suffixIcon: _searchController.text.isNotEmpty
                    ? IconButton(
                        icon: const Icon(Icons.clear_rounded, size: 18),
                        onPressed: () {
                          _searchController.clear();
                          postProvider.setSearchQuery('', lang);
                        },
                      )
                    : null,
              ),
              onSubmitted: (query) => postProvider.setSearchQuery(query, lang),
            ),
          ),

          // Categories Horizontal Filter Chips
          if (postProvider.categories.isNotEmpty)
            SizedBox(
              height: 46,
              child: ListView(
                scrollDirection: Axis.horizontal,
                padding: const EdgeInsets.symmetric(horizontal: 16),
                children: [
                  Padding(
                    padding: const EdgeInsets.only(right: 8),
                    child: FilterChip(
                      selected: postProvider.selectedCategorySlug == null,
                      label: Text(context.tr('all_categories')),
                      onSelected: (_) => postProvider.selectCategory(null, lang),
                      selectedColor: const Color(0xFF4F46E5),
                      labelStyle: TextStyle(
                        color: postProvider.selectedCategorySlug == null
                            ? Colors.white
                            : const Color(0xFF475569),
                        fontSize: 12,
                        fontWeight: FontWeight.w600,
                      ),
                      checkmarkColor: Colors.white,
                      shape: RoundedRectangleBorder(
                        borderRadius: BorderRadius.circular(20),
                      ),
                    ),
                  ),
                  ...postProvider.categories.map((cat) {
                    final isSelected = postProvider.selectedCategorySlug == cat.slug;
                    return Padding(
                      padding: const EdgeInsets.only(right: 8),
                      child: FilterChip(
                        selected: isSelected,
                        label: Text(cat.name),
                        onSelected: (_) => postProvider.selectCategory(cat.slug, lang),
                        selectedColor: const Color(0xFF4F46E5),
                        labelStyle: TextStyle(
                          color: isSelected ? Colors.white : const Color(0xFF475569),
                          fontSize: 12,
                          fontWeight: FontWeight.w600,
                        ),
                        checkmarkColor: Colors.white,
                        shape: RoundedRectangleBorder(
                          borderRadius: BorderRadius.circular(20),
                        ),
                      ),
                    );
                  }),
                ],
              ),
            ),

          const SizedBox(height: 6),

          // Posts List
          Expanded(
            child: RefreshIndicator(
              onRefresh: () async {
                _loadData();
              },
              child: postProvider.isLoading
                  ? const LoadingIndicator()
                  : postProvider.errorMessage != null
                      ? ErrorStateWidget(
                          message: postProvider.errorMessage,
                          onRetry: _loadData,
                        )
                      : postProvider.posts.isEmpty
                          ? Center(
                              child: Text(
                                context.tr('no_posts_found'),
                                style: const TextStyle(
                                  color: Color(0xFF64748B),
                                  fontSize: 14,
                                ),
                              ),
                            )
                          : ListView.builder(
                              physics: const AlwaysScrollableScrollPhysics(),
                              itemCount: postProvider.posts.length,
                              itemBuilder: (context, index) {
                                final post = postProvider.posts[index];
                                return PostCard(
                                  post: post,
                                  onTap: () => context.push('/posts/${post.slug}'),
                                );
                              },
                            ),
            ),
          ),
        ],
      ),
    );
  }
}

