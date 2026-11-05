import 'question.dart';

class Faq {
  final int id;
  final String question;
  final String answer;
  final QuestionTopic? topic;
  final int sortOrder;

  Faq({
    required this.id,
    required this.question,
    required this.answer,
    this.topic,
    required this.sortOrder,
  });

  factory Faq.fromJson(Map<String, dynamic> json) {
    return Faq(
      id: json['id'] ?? 0,
      question: json['question']?.toString() ?? '',
      answer: json['answer']?.toString() ?? '',
      topic: json['topic'] != null
          ? QuestionTopic.fromJson(json['topic'])
          : null,
      sortOrder: json['sort_order'] is int ? json['sort_order'] : 0,
    );
  }
}

