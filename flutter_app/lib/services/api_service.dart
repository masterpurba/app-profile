import 'dart:convert';
import 'package:flutter/foundation.dart' show kDebugMode;
import 'package:http/http.dart' as http;
import '../config/app_config.dart';
import '../models/category.dart';
import '../models/faq.dart';
import '../models/menu_item_model.dart';
import '../models/page_model.dart';
import '../models/post.dart';
import '../models/question.dart';
import '../models/slide.dart';
import '../models/site_setting.dart';

class ApiResponse<T> {
  final T? data;
  final String? message;
  final bool isSuccess;

  ApiResponse.success(this.data)
      : isSuccess = true,
        message = null;

  ApiResponse.error(this.message)
      : isSuccess = false,
        data = null;
}

class ApiService {
  final String languageCode;

  ApiService({this.languageCode = 'id'});

  Map<String, String> get _headers => {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'Accept-Language': languageCode,
      };

  String get _baseUrl => AppConfig.baseUrl;

  // 1. Posts List
  Future<ApiResponse<List<Post>>> getPosts({
    int page = 1,
    int perPage = 10,
    String? categorySlug,
    String? search,
    bool? featured,
  }) async {
    try {
      final queryParams = <String, String>{
        'page': page.toString(),
        'per_page': perPage.toString(),
      };
      if (categorySlug != null && categorySlug.isNotEmpty) {
        queryParams['category'] = categorySlug;
      }
      if (search != null && search.isNotEmpty) {
        queryParams['search'] = search;
      }
      if (featured != null) {
        queryParams['featured'] = featured ? '1' : '0';
      }

      final uri = Uri.parse('$_baseUrl/posts').replace(queryParameters: queryParams);
      final response = await http.get(uri, headers: _headers);

      if (response.statusCode == 200) {
        final body = json.decode(response.body);
        final list = (body['data'] as List<dynamic>)
            .map((e) => Post.fromJson(e))
            .toList();
        return ApiResponse.success(list);
      }
      return ApiResponse.error('Server error: ${response.statusCode}');
    } catch (e) {
      if (kDebugMode) print('getPosts error: $e');
      return ApiResponse.error(e.toString());
    }
  }

  // 2. Post Detail
  Future<ApiResponse<Post>> getPostDetail(String slug) async {
    try {
      final uri = Uri.parse('$_baseUrl/posts/$slug');
      final response = await http.get(uri, headers: _headers);

      if (response.statusCode == 200) {
        final body = json.decode(response.body);
        return ApiResponse.success(Post.fromJson(body['data']));
      }
      return ApiResponse.error('Not found (${response.statusCode})');
    } catch (e) {
      return ApiResponse.error(e.toString());
    }
  }

  // 3. Static Page Detail
  Future<ApiResponse<PageModel>> getPageDetail(String slug) async {
    try {
      final uri = Uri.parse('$_baseUrl/pages/$slug');
      final response = await http.get(uri, headers: _headers);

      if (response.statusCode == 200) {
        final body = json.decode(response.body);
        return ApiResponse.success(PageModel.fromJson(body['data']));
      }
      return ApiResponse.error('Not found (${response.statusCode})');
    } catch (e) {
      return ApiResponse.error(e.toString());
    }
  }

  // 4. Categories
  Future<ApiResponse<List<Category>>> getCategories() async {
    try {
      final uri = Uri.parse('$_baseUrl/categories');
      final response = await http.get(uri, headers: _headers);

      if (response.statusCode == 200) {
        final body = json.decode(response.body);
        final list = (body['data'] as List<dynamic>)
            .map((e) => Category.fromJson(e))
            .toList();
        return ApiResponse.success(list);
      }
      return ApiResponse.error('Error fetching categories');
    } catch (e) {
      return ApiResponse.error(e.toString());
    }
  }

  // 5. Slides Banner
  Future<ApiResponse<List<Slide>>> getSlides() async {
    try {
      final uri = Uri.parse('$_baseUrl/slides');
      final response = await http.get(uri, headers: _headers);

      if (response.statusCode == 200) {
        final body = json.decode(response.body);
        final list = (body['data'] as List<dynamic>)
            .map((e) => Slide.fromJson(e))
            .toList();
        return ApiResponse.success(list);
      }
      return ApiResponse.error('Error fetching slides');
    } catch (e) {
      return ApiResponse.error(e.toString());
    }
  }

  // 6. Menus
  Future<ApiResponse<MenuModel>> getMenu(String location) async {
    try {
      final uri = Uri.parse('$_baseUrl/menus/$location');
      final response = await http.get(uri, headers: _headers);

      if (response.statusCode == 200) {
        final body = json.decode(response.body);
        return ApiResponse.success(MenuModel.fromJson(body['data']));
      }
      return ApiResponse.error('Menu not found');
    } catch (e) {
      return ApiResponse.error(e.toString());
    }
  }

  // 7. Site Settings
  Future<ApiResponse<Map<String, String>>> getSettings() async {
    try {
      final uri = Uri.parse('$_baseUrl/settings');
      final response = await http.get(uri, headers: _headers);

      if (response.statusCode == 200) {
        final body = json.decode(response.body);
        final list = (body['data'] as List<dynamic>)
            .map((e) => SiteSetting.fromJson(e))
            .toList();
        final map = <String, String>{};
        for (var s in list) {
          if (s.value != null) {
            map[s.key] = s.value!;
          }
        }
        return ApiResponse.success(map);
      }
      return ApiResponse.error('Error fetching settings');
    } catch (e) {
      return ApiResponse.error(e.toString());
    }
  }

  // 8. Question Topics
  Future<ApiResponse<List<QuestionTopic>>> getQuestionTopics() async {
    try {
      final uri = Uri.parse('$_baseUrl/question-topics');
      final response = await http.get(uri, headers: _headers);

      if (response.statusCode == 200) {
        final body = json.decode(response.body);
        final list = (body['data'] as List<dynamic>)
            .map((e) => QuestionTopic.fromJson(e))
            .toList();
        return ApiResponse.success(list);
      }
      return ApiResponse.error('Error fetching topics');
    } catch (e) {
      return ApiResponse.error(e.toString());
    }
  }

  // 9. Answered Public Questions
  Future<ApiResponse<List<Question>>> getQuestions({
    int? topicId,
    String? search,
    int page = 1,
  }) async {
    try {
      final queryParams = <String, String>{'page': page.toString()};
      if (topicId != null) queryParams['topic_id'] = topicId.toString();
      if (search != null && search.isNotEmpty) queryParams['search'] = search;

      final uri = Uri.parse('$_baseUrl/questions').replace(queryParameters: queryParams);
      final response = await http.get(uri, headers: _headers);

      if (response.statusCode == 200) {
        final body = json.decode(response.body);
        final list = (body['data'] as List<dynamic>)
            .map((e) => Question.fromJson(e))
            .toList();
        return ApiResponse.success(list);
      }
      return ApiResponse.error('Error fetching questions');
    } catch (e) {
      return ApiResponse.error(e.toString());
    }
  }

  // 10. Submit New Question
  Future<ApiResponse<bool>> submitQuestion({
    required int topicId,
    required String name,
    required String email,
    String? phone,
    required String subject,
    required String body,
  }) async {
    try {
      final uri = Uri.parse('$_baseUrl/questions');
      final payload = json.encode({
        'topic_id': topicId,
        'name': name,
        'email': email,
        'phone': phone,
        'subject': subject,
        'body': body,
      });

      final response = await http.post(uri, headers: _headers, body: payload);

      if (response.statusCode == 201 || response.statusCode == 200) {
        return ApiResponse.success(true);
      } else if (response.statusCode == 422) {
        final res = json.decode(response.body);
        return ApiResponse.error(res['message'] ?? 'Validation error');
      }
      return ApiResponse.error('Failed to submit question (${response.statusCode})');
    } catch (e) {
      return ApiResponse.error(e.toString());
    }
  }

  // 11. FAQs
  Future<ApiResponse<List<Faq>>> getFaqs({int? topicId}) async {
    try {
      final queryParams = <String, String>{};
      if (topicId != null) queryParams['topic_id'] = topicId.toString();

      final uri = Uri.parse('$_baseUrl/faqs').replace(queryParameters: queryParams);
      final response = await http.get(uri, headers: _headers);

      if (response.statusCode == 200) {
        final body = json.decode(response.body);
        final list = (body['data'] as List<dynamic>)
            .map((e) => Faq.fromJson(e))
            .toList();
        return ApiResponse.success(list);
      }
      return ApiResponse.error('Error fetching FAQs');
    } catch (e) {
      return ApiResponse.error(e.toString());
    }
  }
}
