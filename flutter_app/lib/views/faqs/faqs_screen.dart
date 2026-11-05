import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../../l10n/app_localizations.dart';
import '../../models/faq.dart';
import '../../providers/faq_provider.dart';
import '../../providers/locale_provider.dart';
import '../../widgets/error_state.dart';
import '../../widgets/loading_indicator.dart';

class FaqsScreen extends StatefulWidget {
  const FaqsScreen({super.key});

  @override
  State<FaqsScreen> createState() => _FaqsScreenState();
}

class _FaqsScreenState extends State<FaqsScreen> {
  @override
  void initState() {
    super.initState();
    WidgetsBinding.instance.addPostFrameCallback((_) {
      _loadData();
    });
  }

  void _loadData() {
    final lang = context.read<LocaleProvider>().locale.languageCode;
    context.read<FaqProvider>().init(lang);
  }

  @override
  Widget build(BuildContext context) {
    final lang = context.watch<LocaleProvider>().locale.languageCode;
    final faqProvider = context.watch<FaqProvider>();

    return Scaffold(
      appBar: AppBar(
        title: Text(context.tr('nav_faqs')),
      ),
      body: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Padding(
            padding: const EdgeInsets.fromLTRB(16, 12, 16, 8),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  context.tr('faq_title'),
                  style: const TextStyle(
                    fontSize: 18,
                    fontWeight: FontWeight.bold,
                    color: Color(0xFF0F172A),
                  ),
                ),
                const SizedBox(height: 4),
                Text(
                  context.tr('faq_subtitle'),
                  style: const TextStyle(fontSize: 13, color: Color(0xFF64748B)),
                ),
              ],
            ),
          ),

          // Topic Chips
          if (faqProvider.topics.isNotEmpty)
            SizedBox(
              height: 44,
              child: ListView(
                scrollDirection: Axis.horizontal,
                padding: const EdgeInsets.symmetric(horizontal: 16),
                children: [
                  Padding(
                    padding: const EdgeInsets.only(right: 8),
                    child: FilterChip(
                      selected: faqProvider.selectedTopicId == null,
                      label: Text(context.tr('all_topics')),
                      onSelected: (_) => faqProvider.selectTopic(null, lang),
                      selectedColor: const Color(0xFF4F46E5),
                      labelStyle: TextStyle(
                        color: faqProvider.selectedTopicId == null
                            ? Colors.white
                            : const Color(0xFF475569),
                        fontSize: 12,
                        fontWeight: FontWeight.w600,
                      ),
                      checkmarkColor: Colors.white,
                      shape: RoundedRectangleBorder(
                        borderRadius: BorderRadius.circular(20),
                      ),
                    ),
                  ),
                  ...faqProvider.topics.map((t) {
                    final isSelected = faqProvider.selectedTopicId == t.id;
                    return Padding(
                      padding: const EdgeInsets.only(right: 8),
                      child: FilterChip(
                        selected: isSelected,
                        label: Text(t.name),
                        onSelected: (_) => faqProvider.selectTopic(t.id, lang),
                        selectedColor: const Color(0xFF4F46E5),
                        labelStyle: TextStyle(
                          color: isSelected ? Colors.white : const Color(0xFF475569),
                          fontSize: 12,
                          fontWeight: FontWeight.w600,
                        ),
                        checkmarkColor: Colors.white,
                        shape: RoundedRectangleBorder(
                          borderRadius: BorderRadius.circular(20),
                        ),
                      ),
                    );
                  }),
                ],
              ),
            ),

          const SizedBox(height: 8),

          Expanded(
            child: RefreshIndicator(
              onRefresh: () async => _loadData(),
              child: faqProvider.isLoading
                  ? const LoadingIndicator()
                  : faqProvider.errorMessage != null
                      ? ErrorStateWidget(
                          message: faqProvider.errorMessage,
                          onRetry: _loadData,
                        )
                      : faqProvider.faqs.isEmpty
                          ? Center(
                              child: Text(
                                context.tr('no_faqs_found'),
                                style: const TextStyle(
                                  color: Color(0xFF64748B),
                                  fontSize: 14,
                                ),
                              ),
                            )
                          : ListView.builder(
                              physics: const AlwaysScrollableScrollPhysics(),
                              padding: const EdgeInsets.symmetric(
                                horizontal: 16,
                                vertical: 8,
                              ),
                              itemCount: faqProvider.faqs.length,
                              itemBuilder: (context, index) {
                                final faq = faqProvider.faqs[index];
                                return _buildFaqItem(faq);
                              },
                            ),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildFaqItem(Faq faq) {
    return Card(
      margin: const EdgeInsets.only(bottom: 12),
      shape: RoundedRectangleBorder(
        borderRadius: BorderRadius.circular(16),
        side: const BorderSide(color: Color(0xFFE2E8F0)),
      ),
      elevation: 0,
      clipBehavior: Clip.antiAlias,
      child: Theme(
        data: Theme.of(context).copyWith(dividerColor: Colors.transparent),
        child: ExpansionTile(
          tilePadding: const EdgeInsets.symmetric(horizontal: 16, vertical: 6),
          leading: Container(
            padding: const EdgeInsets.all(8),
            decoration: BoxDecoration(
              color: const Color(0xFFEEF2FF),
              borderRadius: BorderRadius.circular(10),
            ),
            child: const Icon(
              Icons.help_outline_rounded,
              color: Color(0xFF4F46E5),
              size: 20,
            ),
          ),
          title: Text(
            faq.question,
            style: const TextStyle(
              fontSize: 15,
              fontWeight: FontWeight.w700,
              color: Color(0xFF0F172A),
            ),
          ),
          subtitle: faq.topic != null
              ? Padding(
                  padding: const EdgeInsets.only(top: 4),
                  child: Text(
                    faq.topic!.name,
                    style: const TextStyle(
                      fontSize: 11,
                      color: Color(0xFF4F46E5),
                      fontWeight: FontWeight.w600,
                    ),
                  ),
                )
              : null,
          children: [
            Container(
              width: double.infinity,
              padding: const EdgeInsets.fromLTRB(16, 0, 16, 16),
              child: Container(
                padding: const EdgeInsets.all(14),
                decoration: BoxDecoration(
                  color: const Color(0xFFF8FAFC),
                  borderRadius: BorderRadius.circular(12),
                ),
                child: Text(
                  faq.answer,
                  style: const TextStyle(
                    fontSize: 14,
                    color: Color(0xFF334155),
                    height: 1.6,
                  ),
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }
}

