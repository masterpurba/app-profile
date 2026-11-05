class Category {
  final int id;
  final String name;
  final String slug;
  final String? description;
  final String? icon;

  Category({
    required this.id,
    required this.name,
    required this.slug,
    this.description,
    this.icon,
  });

  factory Category.fromJson(Map<String, dynamic> json) {
    return Category(
      id: json['id'] ?? 0,
      name: json['name']?.toString() ?? '',
      slug: json['slug']?.toString() ?? '',
      description: json['description']?.toString(),
      icon: json['icon']?.toString(),
    );
  }
}

