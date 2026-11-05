import 'dart:io' show Platform;
import 'package:flutter/foundation.dart';
import 'package:shared_preferences/shared_preferences.dart';

class AppConfig {
  static const String _baseUrlKey = 'custom_base_url';

  /// Standard presets for development, testing, and production
  static const String liveBaseUrl = 'https://cmslayanan.freedev.app/api/v1';
  static const String genymotionBaseUrl = 'http://10.0.3.2:8000/api/v1';
  static const String standardEmulatorBaseUrl = 'http://10.0.2.2:8000/api/v1';
  static const String localWebBaseUrl = 'http://127.0.0.1:8000/api/v1';

  /// Auto-detect appropriate default base URL based on running platform
  static String get defaultBaseUrl {
    if (kIsWeb) {
      return localWebBaseUrl;
    }
    try {
      if (Platform.isAndroid) {
        // Genymotion uses 10.0.3.2 to route to the host machine
        return genymotionBaseUrl;
      }
    } catch (_) {}
    return localWebBaseUrl;
  }

  static String _baseUrl = '';

  static String get baseUrl => _baseUrl.isNotEmpty ? _baseUrl : defaultBaseUrl;

  static Future<void> init() async {
    final prefs = await SharedPreferences.getInstance();
    final saved = prefs.getString(_baseUrlKey);
    // If user was previously assigned the unreachable live URL during local dev/testing,
    // automatically fall back to the working platform default
    if (saved != null && saved.contains('cmslayanan.freedev.app')) {
      await prefs.remove(_baseUrlKey);
      _baseUrl = defaultBaseUrl;
    } else {
      _baseUrl = saved ?? defaultBaseUrl;
    }
  }

  static Future<void> setBaseUrl(String url) async {
    _baseUrl = url.trim();
    final prefs = await SharedPreferences.getInstance();
    await prefs.setString(_baseUrlKey, _baseUrl);
  }

  static Future<void> resetBaseUrl() async {
    _baseUrl = defaultBaseUrl;
    final prefs = await SharedPreferences.getInstance();
    await prefs.remove(_baseUrlKey);
  }
}
