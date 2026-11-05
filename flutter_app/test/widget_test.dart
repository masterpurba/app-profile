import 'package:flutter_test/flutter_test.dart';
import 'package:provider/provider.dart';
import 'package:flutter_app/config/app_config.dart';
import 'package:flutter_app/main.dart';
import 'package:flutter_app/providers/faq_provider.dart';
import 'package:flutter_app/providers/home_provider.dart';
import 'package:flutter_app/providers/locale_provider.dart';
import 'package:flutter_app/providers/post_provider.dart';
import 'package:flutter_app/providers/question_provider.dart';
import 'package:shared_preferences/shared_preferences.dart';

void main() {
  TestWidgetsFlutterBinding.ensureInitialized();

  testWidgets('App smoke test', (WidgetTester tester) async {
    SharedPreferences.setMockInitialValues({});
    await AppConfig.init();

    await tester.pumpWidget(
      MultiProvider(
        providers: [
          ChangeNotifierProvider(create: (_) => LocaleProvider()),
          ChangeNotifierProvider(create: (_) => HomeProvider()),
          ChangeNotifierProvider(create: (_) => PostProvider()),
          ChangeNotifierProvider(create: (_) => QuestionProvider()),
          ChangeNotifierProvider(create: (_) => FaqProvider()),
        ],
        child: const MainApp(),
      ),
    );

    await tester.pump();
    expect(find.byType(MainApp), findsOneWidget);
  });
}
