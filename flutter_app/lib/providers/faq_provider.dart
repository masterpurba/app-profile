import 'package:flutter/material.dart';
import '../models/faq.dart';
import '../models/question.dart';
import '../services/api_service.dart';

class FaqProvider extends ChangeNotifier {
  List<Faq> faqs = [];
  List<QuestionTopic> topics = [];
  int? selectedTopicId;

  bool isLoading = false;
  String? errorMessage;

  Future<void> init(String lang) async {
    await fetchTopics(lang);
    await fetchFaqs(lang);
  }

  Future<void> fetchTopics(String lang) async {
    final api = ApiService(languageCode: lang);
    final res = await api.getQuestionTopics();
    if (res.isSuccess) {
      topics = res.data ?? [];
      notifyListeners();
    }
  }

  Future<void> fetchFaqs(String lang) async {
    isLoading = true;
    errorMessage = null;
    notifyListeners();

    final api = ApiService(languageCode: lang);
    final res = await api.getFaqs(topicId: selectedTopicId);

    isLoading = false;
    if (res.isSuccess) {
      faqs = res.data ?? [];
    } else {
      errorMessage = res.message;
    }
    notifyListeners();
  }

  void selectTopic(int? topicId, String lang) {
    selectedTopicId = selectedTopicId == topicId ? null : topicId;
    fetchFaqs(lang);
  }
}

