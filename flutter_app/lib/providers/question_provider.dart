import 'package:flutter/material.dart';
import '../models/question.dart';
import '../services/api_service.dart';

class QuestionProvider extends ChangeNotifier {
  List<Question> questions = [];
  List<QuestionTopic> topics = [];
  int? selectedTopicId;
  String searchQuery = '';

  bool isLoading = false;
  bool isSubmitting = false;
  String? errorMessage;
  String? submitError;

  Future<void> init(String lang) async {
    await fetchTopics(lang);
    await fetchQuestions(lang);
  }

  Future<void> fetchTopics(String lang) async {
    final api = ApiService(languageCode: lang);
    final res = await api.getQuestionTopics();
    if (res.isSuccess) {
      topics = res.data ?? [];
      notifyListeners();
    }
  }

  Future<void> fetchQuestions(String lang) async {
    isLoading = true;
    errorMessage = null;
    notifyListeners();

    final api = ApiService(languageCode: lang);
    final res = await api.getQuestions(
      topicId: selectedTopicId,
      search: searchQuery.isNotEmpty ? searchQuery : null,
    );

    isLoading = false;
    if (res.isSuccess) {
      questions = res.data ?? [];
    } else {
      errorMessage = res.message;
    }
    notifyListeners();
  }

  void selectTopic(int? topicId, String lang) {
    selectedTopicId = selectedTopicId == topicId ? null : topicId;
    fetchQuestions(lang);
  }

  void setSearchQuery(String query, String lang) {
    searchQuery = query;
    fetchQuestions(lang);
  }

  Future<bool> submitQuestion({
    required int topicId,
    required String name,
    required String email,
    String? phone,
    required String subject,
    required String body,
    required String lang,
  }) async {
    isSubmitting = true;
    submitError = null;
    notifyListeners();

    final api = ApiService(languageCode: lang);
    final res = await api.submitQuestion(
      topicId: topicId,
      name: name,
      email: email,
      phone: phone,
      subject: subject,
      body: body,
    );

    isSubmitting = false;
    if (res.isSuccess) {
      notifyListeners();
      return true;
    } else {
      submitError = res.message ?? 'Failed to submit question';
      notifyListeners();
      return false;
    }
  }
}

