class PageModel {
  final int id;
  final String title;
  final String slug;
  final String content;
  final String? imageUrl;
  final String status;
  final int sortOrder;
  final bool showInMenu;
  final String? updatedAt;

  PageModel({
    required this.id,
    required this.title,
    required this.slug,
    required this.content,
    this.imageUrl,
    required this.status,
    required this.sortOrder,
    required this.showInMenu,
    this.updatedAt,
  });

  factory PageModel.fromJson(Map<String, dynamic> json) {
    return PageModel(
      id: json['id'] ?? 0,
      title: json['title']?.toString() ?? '',
      slug: json['slug']?.toString() ?? '',
      content: json['content']?.toString() ?? '',
      imageUrl: json['image_url']?.toString(),
      status: json['status']?.toString() ?? 'published',
      sortOrder: json['sort_order'] is int ? json['sort_order'] : 0,
      showInMenu: json['show_in_menu'] == true,
      updatedAt: json['updated_at']?.toString(),
    );
  }
}

