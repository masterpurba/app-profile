import 'package:flutter/material.dart';
import 'package:shared_preferences/shared_preferences.dart';

class LocaleProvider extends ChangeNotifier {
  static const String _localeKey = 'app_locale';

  Locale _locale = const Locale('id');

  Locale get locale => _locale;

  bool get isIndonesian => _locale.languageCode == 'id';

  LocaleProvider() {
    _loadLocale();
  }

  Future<void> _loadLocale() async {
    final prefs = await SharedPreferences.getInstance();
    final savedCode = prefs.getString(_localeKey);
    if (savedCode != null && ['id', 'en'].contains(savedCode)) {
      _locale = Locale(savedCode);
      notifyListeners();
    }
  }

  Future<void> setLocale(Locale newLocale) async {
    if (!['id', 'en'].contains(newLocale.languageCode)) return;
    if (_locale == newLocale) return;

    _locale = newLocale;
    notifyListeners();

    final prefs = await SharedPreferences.getInstance();
    await prefs.setString(_localeKey, newLocale.languageCode);
  }

  Future<void> toggleLocale() async {
    final next = isIndonesian ? const Locale('en') : const Locale('id');
    await setLocale(next);
  }
}

