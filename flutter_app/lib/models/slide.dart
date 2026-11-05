class Slide {
  final int id;
  final String title;
  final String? description;
  final String? imageUrl;
  final String? linkUrl;
  final int sortOrder;

  Slide({
    required this.id,
    required this.title,
    this.description,
    this.imageUrl,
    this.linkUrl,
    required this.sortOrder,
  });

  factory Slide.fromJson(Map<String, dynamic> json) {
    return Slide(
      id: json['id'] ?? 0,
      title: json['title']?.toString() ?? '',
      description: json['description']?.toString(),
      imageUrl: json['image_url']?.toString(),
      linkUrl: json['link_url']?.toString(),
      sortOrder: json['sort_order'] is int ? json['sort_order'] : 0,
    );
  }
}

