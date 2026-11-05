import 'category.dart';

class Author {
  final String name;
  final String? avatarUrl;

  Author({required this.name, this.avatarUrl});

  factory Author.fromJson(Map<String, dynamic>? json) {
    if (json == null) return Author(name: 'Admin');
    return Author(
      name: json['name']?.toString() ?? 'Admin',
      avatarUrl: json['avatar_url']?.toString(),
    );
  }
}

class Post {
  final int id;
  final String title;
  final String slug;
  final String? summary;
  final String? content;
  final String? imageUrl;
  final String status;
  final String? publishedAt;
  final int viewCount;
  final bool isFeatured;
  final Category? category;
  final Author author;
  final String? createdAt;

  Post({
    required this.id,
    required this.title,
    required this.slug,
    this.summary,
    this.content,
    this.imageUrl,
    required this.status,
    this.publishedAt,
    required this.viewCount,
    required this.isFeatured,
    this.category,
    required this.author,
    this.createdAt,
  });

  factory Post.fromJson(Map<String, dynamic> json) {
    return Post(
      id: json['id'] ?? 0,
      title: json['title']?.toString() ?? '',
      slug: json['slug']?.toString() ?? '',
      summary: json['summary']?.toString(),
      content: json['content']?.toString(),
      imageUrl: json['image_url']?.toString(),
      status: json['status']?.toString() ?? 'published',
      publishedAt: json['published_at']?.toString(),
      viewCount: json['view_count'] is int ? json['view_count'] : 0,
      isFeatured: json['is_featured'] == true,
      category: json['category'] != null
          ? Category.fromJson(json['category'])
          : null,
      author: Author.fromJson(json['author']),
      createdAt: json['created_at']?.toString(),
    );
  }
}

