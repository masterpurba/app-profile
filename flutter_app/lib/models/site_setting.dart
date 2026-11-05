class SiteSetting {
  final String group;
  final String key;
  final String? value;
  final String type;

  SiteSetting({
    required this.group,
    required this.key,
    this.value,
    required this.type,
  });

  factory SiteSetting.fromJson(Map<String, dynamic> json) {
    return SiteSetting(
      group: json['group']?.toString() ?? 'general',
      key: json['key']?.toString() ?? '',
      value: json['value']?.toString(),
      type: json['type']?.toString() ?? 'text',
    );
  }
}

