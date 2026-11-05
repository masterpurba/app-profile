import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import '../views/faqs/faqs_screen.dart';
import '../views/home/home_screen.dart';
import '../views/pages/page_detail_screen.dart';
import '../views/posts/post_detail_screen.dart';
import '../views/posts/post_list_screen.dart';
import '../views/questions/questions_screen.dart';
import '../views/settings/settings_screen.dart';
import '../views/shell_view.dart';

final GlobalKey<NavigatorState> _rootNavigatorKey =
    GlobalKey<NavigatorState>(debugLabel: 'root');

class AppRoutes {
  static final GoRouter router = GoRouter(
    navigatorKey: _rootNavigatorKey,
    initialLocation: '/',
    routes: [
      StatefulShellRoute.indexedStack(
        builder: (context, state, navigationShell) {
          return ShellView(navigationShell: navigationShell);
        },
        branches: [
          // 0. Home
          StatefulShellBranch(
            routes: [
              GoRoute(
                path: '/',
                builder: (context, state) => const HomeScreen(),
              ),
            ],
          ),

          // 1. Posts List
          StatefulShellBranch(
            routes: [
              GoRoute(
                path: '/posts',
                builder: (context, state) => const PostListScreen(),
              ),
            ],
          ),

          // 2. Questions & Submit
          StatefulShellBranch(
            routes: [
              GoRoute(
                path: '/questions',
                builder: (context, state) => const QuestionsScreen(),
              ),
            ],
          ),

          // 3. FAQs
          StatefulShellBranch(
            routes: [
              GoRoute(
                path: '/faqs',
                builder: (context, state) => const FaqsScreen(),
              ),
            ],
          ),

          // 4. Settings & Agency Info
          StatefulShellBranch(
            routes: [
              GoRoute(
                path: '/settings',
                builder: (context, state) => const SettingsScreen(),
              ),
            ],
          ),
        ],
      ),

      // Post Detail Screen
      GoRoute(
        parentNavigatorKey: _rootNavigatorKey,
        path: '/posts/:slug',
        builder: (context, state) {
          final slug = state.pathParameters['slug'] ?? '';
          return PostDetailScreen(slug: slug);
        },
      ),

      // Static Page Detail Screen
      GoRoute(
        parentNavigatorKey: _rootNavigatorKey,
        path: '/pages/:slug',
        builder: (context, state) {
          final slug = state.pathParameters['slug'] ?? '';
          return PageDetailScreen(slug: slug);
        },
      ),
    ],
  );
}

