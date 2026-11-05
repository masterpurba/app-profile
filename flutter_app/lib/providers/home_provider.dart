import 'package:flutter/material.dart';
import '../models/menu_item_model.dart';
import '../models/post.dart';
import '../models/slide.dart';
import '../services/api_service.dart';

class HomeProvider extends ChangeNotifier {
  List<Slide> slides = [];
  List<MenuItemModel> quickMenuItems = [];
  Post? featuredPost;
  List<Post> latestPosts = [];

  bool isLoading = true;
  String? errorMessage;

  Future<void> fetchHomeData(String lang) async {
    isLoading = true;
    errorMessage = null;
    notifyListeners();

    final api = ApiService(languageCode: lang);

    try {
      final results = await Future.wait([
        api.getSlides(),
        api.getPosts(featured: true, perPage: 1),
        api.getPosts(perPage: 6),
        api.getMenu('header'),
      ]);

      final slidesRes = results[0] as ApiResponse<List<Slide>>;
      final featuredRes = results[1] as ApiResponse<List<Post>>;
      final latestRes = results[2] as ApiResponse<List<Post>>;
      final menuRes = results[3] as ApiResponse<MenuModel>;

      if (slidesRes.isSuccess) {
        slides = slidesRes.data ?? [];
      }
      if (featuredRes.isSuccess && (featuredRes.data?.isNotEmpty ?? false)) {
        featuredPost = featuredRes.data!.first;
      }
      if (latestRes.isSuccess) {
        latestPosts = latestRes.data ?? [];
      }
      if (menuRes.isSuccess) {
        quickMenuItems = menuRes.data?.items ?? [];
      }

      isLoading = false;
      notifyListeners();
    } catch (e) {
      isLoading = false;
      errorMessage = e.toString();
      notifyListeners();
    }
  }
}

