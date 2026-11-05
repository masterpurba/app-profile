class MenuItemModel {
  final int id;
  final String label;
  final String? url;
  final String type;
  final int sortOrder;
  final List<MenuItemModel> children;

  MenuItemModel({
    required this.id,
    required this.label,
    this.url,
    required this.type,
    required this.sortOrder,
    this.children = const [],
  });

  factory MenuItemModel.fromJson(Map<String, dynamic> json) {
    var rawChildren = json['children'] as List<dynamic>?;
    return MenuItemModel(
      id: json['id'] ?? 0,
      label: json['label']?.toString() ?? '',
      url: json['url']?.toString(),
      type: json['type']?.toString() ?? 'custom',
      sortOrder: json['sort_order'] is int ? json['sort_order'] : 0,
      children: rawChildren != null
          ? rawChildren.map((e) => MenuItemModel.fromJson(e)).toList()
          : [],
    );
  }
}

class MenuModel {
  final int id;
  final String name;
  final String location;
  final List<MenuItemModel> items;

  MenuModel({
    required this.id,
    required this.name,
    required this.location,
    this.items = const [],
  });

  factory MenuModel.fromJson(Map<String, dynamic> json) {
    var rawItems = json['items'] as List<dynamic>?;
    return MenuModel(
      id: json['id'] ?? 0,
      name: json['name']?.toString() ?? '',
      location: json['location']?.toString() ?? '',
      items: rawItems != null
          ? rawItems.map((e) => MenuItemModel.fromJson(e)).toList()
          : [],
    );
  }
}

