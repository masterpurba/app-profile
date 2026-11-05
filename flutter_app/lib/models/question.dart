class QuestionTopic {
  final int id;
  final String name;
  final String slug;
  final String? description;
  final String? icon;

  QuestionTopic({
    required this.id,
    required this.name,
    required this.slug,
    this.description,
    this.icon,
  });

  factory QuestionTopic.fromJson(Map<String, dynamic> json) {
    return QuestionTopic(
      id: json['id'] ?? 0,
      name: json['name']?.toString() ?? '',
      slug: json['slug']?.toString() ?? '',
      description: json['description']?.toString(),
      icon: json['icon']?.toString(),
    );
  }
}

class QuestionAnswer {
  final int id;
  final String body;
  final String answeredBy;
  final String? answeredAt;

  QuestionAnswer({
    required this.id,
    required this.body,
    required this.answeredBy,
    this.answeredAt,
  });

  factory QuestionAnswer.fromJson(Map<String, dynamic> json) {
    return QuestionAnswer(
      id: json['id'] ?? 0,
      body: json['body']?.toString() ?? '',
      answeredBy: json['answered_by']?.toString() ?? 'Admin',
      answeredAt: json['answered_at']?.toString(),
    );
  }
}

class Question {
  final int id;
  final String subject;
  final String body;
  final String name;
  final String status;
  final QuestionTopic? topic;
  final List<QuestionAnswer> answers;
  final String? createdAt;

  Question({
    required this.id,
    required this.subject,
    required this.body,
    required this.name,
    required this.status,
    this.topic,
    this.answers = const [],
    this.createdAt,
  });

  factory Question.fromJson(Map<String, dynamic> json) {
    var rawAnswers = json['answers'] as List<dynamic>?;
    return Question(
      id: json['id'] ?? 0,
      subject: json['subject']?.toString() ?? '',
      body: json['body']?.toString() ?? '',
      name: json['name']?.toString() ?? '',
      status: json['status']?.toString() ?? 'pending',
      topic: json['topic'] != null
          ? QuestionTopic.fromJson(json['topic'])
          : null,
      answers: rawAnswers != null
          ? rawAnswers.map((a) => QuestionAnswer.fromJson(a)).toList()
          : [],
      createdAt: json['created_at']?.toString(),
    );
  }
}

