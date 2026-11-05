import 'package:flutter/material.dart';
import '../models/category.dart';
import '../models/post.dart';
import '../services/api_service.dart';

class PostProvider extends ChangeNotifier {
  List<Post> posts = [];
  List<Category> categories = [];
  String? selectedCategorySlug;
  String searchQuery = '';

  bool isLoading = false;
  String? errorMessage;

  Future<void> init(String lang) async {
    await fetchCategories(lang);
    await fetchPosts(lang);
  }

  Future<void> fetchCategories(String lang) async {
    final api = ApiService(languageCode: lang);
    final res = await api.getCategories();
    if (res.isSuccess) {
      categories = res.data ?? [];
      notifyListeners();
    }
  }

  Future<void> fetchPosts(String lang) async {
    isLoading = true;
    errorMessage = null;
    notifyListeners();

    final api = ApiService(languageCode: lang);
    final res = await api.getPosts(
      categorySlug: selectedCategorySlug,
      search: searchQuery.isNotEmpty ? searchQuery : null,
    );

    isLoading = false;
    if (res.isSuccess) {
      posts = res.data ?? [];
    } else {
      errorMessage = res.message ?? 'Failed to load posts';
    }
    notifyListeners();
  }

  void selectCategory(String? slug, String lang) {
    if (selectedCategorySlug == slug) {
      selectedCategorySlug = null;
    } else {
      selectedCategorySlug = slug;
    }
    fetchPosts(lang);
  }

  void setSearchQuery(String query, String lang) {
    searchQuery = query;
    fetchPosts(lang);
  }
}

