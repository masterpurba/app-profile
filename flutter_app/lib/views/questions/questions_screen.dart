import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../../l10n/app_localizations.dart';
import '../../models/question.dart';
import '../../providers/locale_provider.dart';
import '../../providers/question_provider.dart';
import '../../widgets/error_state.dart';
import '../../widgets/loading_indicator.dart';

class QuestionsScreen extends StatefulWidget {
  const QuestionsScreen({super.key});

  @override
  State<QuestionsScreen> createState() => _QuestionsScreenState();
}

class _QuestionsScreenState extends State<QuestionsScreen>
    with SingleTickerProviderStateMixin {
  late final TabController _tabController;

  // Form Controllers
  final _formKey = GlobalKey<FormState>();
  final _nameController = TextEditingController();
  final _emailController = TextEditingController();
  final _phoneController = TextEditingController();
  final _subjectController = TextEditingController();
  final _bodyController = TextEditingController();
  int? _selectedTopicId;

  final TextEditingController _searchController = TextEditingController();

  @override
  void initState() {
    super.initState();
    _tabController = TabController(length: 2, vsync: this);
    WidgetsBinding.instance.addPostFrameCallback((_) {
      _loadData();
    });
  }

  void _loadData() {
    final lang = context.read<LocaleProvider>().locale.languageCode;
    context.read<QuestionProvider>().init(lang);
  }

  @override
  void dispose() {
    _tabController.dispose();
    _nameController.dispose();
    _emailController.dispose();
    _phoneController.dispose();
    _subjectController.dispose();
    _bodyController.dispose();
    _searchController.dispose();
    super.dispose();
  }

  Future<void> _submitForm() async {
    if (!_formKey.currentState!.validate()) return;
    if (_selectedTopicId == null) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text(context.tr('form_topic_hint'))),
      );
      return;
    }

    final lang = context.read<LocaleProvider>().locale.languageCode;
    final questionProvider = context.read<QuestionProvider>();

    final success = await questionProvider.submitQuestion(
      topicId: _selectedTopicId!,
      name: _nameController.text.trim(),
      email: _emailController.text.trim(),
      phone: _phoneController.text.trim(),
      subject: _subjectController.text.trim(),
      body: _bodyController.text.trim(),
      lang: lang,
    );

    if (mounted) {
      if (success) {
        // Reset form
        _nameController.clear();
        _emailController.clear();
        _phoneController.clear();
        _subjectController.clear();
        _bodyController.clear();
        setState(() => _selectedTopicId = null);

        // Show Success Dialog
        showDialog(
          context: context,
          builder: (ctx) => AlertDialog(
            icon: const Icon(
              Icons.check_circle_outline_rounded,
              color: Color(0xFF10B981),
              size: 48,
            ),
            title: Text(context.tr('submit_success_title')),
            content: Text(
              context.tr('submit_success_desc'),
              textAlign: TextAlign.center,
              style: const TextStyle(fontSize: 14, color: Color(0xFF475569)),
            ),
            actions: [
              FilledButton(
                onPressed: () {
                  Navigator.pop(ctx);
                  _tabController.animateTo(0);
                  _loadData();
                },
                child: Text(context.tr('btn_back_to_home')),
              ),
            ],
          ),
        );
      } else {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text(
              questionProvider.submitError ?? context.tr('error_subtitle'),
            ),
            backgroundColor: Colors.red.shade600,
          ),
        );
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    final lang = context.watch<LocaleProvider>().locale.languageCode;
    final questionProvider = context.watch<QuestionProvider>();

    return Scaffold(
      appBar: AppBar(
        title: Text(context.tr('nav_questions')),
        bottom: TabBar(
          controller: _tabController,
          labelColor: const Color(0xFF4F46E5),
          indicatorColor: const Color(0xFF4F46E5),
          unselectedLabelColor: const Color(0xFF64748B),
          tabs: [
            Tab(
              icon: const Icon(Icons.forum_outlined, size: 20),
              text: context.tr('tab_public_questions'),
            ),
            Tab(
              icon: const Icon(Icons.edit_note_rounded, size: 20),
              text: context.tr('tab_ask_question'),
            ),
          ],
        ),
      ),
      body: TabBarView(
        controller: _tabController,
        children: [
          // TAB 1: Answered Public Questions
          _buildAnsweredQuestionsTab(context, questionProvider, lang),

          // TAB 2: Submit Question Form
          _buildSubmitFormTab(context, questionProvider),
        ],
      ),
    );
  }

  Widget _buildAnsweredQuestionsTab(
    BuildContext context,
    QuestionProvider provider,
    String lang,
  ) {
    return Column(
      children: [
        // Search & Filter
        Padding(
          padding: const EdgeInsets.fromLTRB(16, 12, 16, 8),
          child: TextField(
            controller: _searchController,
            decoration: InputDecoration(
              hintText: context.tr('search_questions_hint'),
              hintStyle: const TextStyle(fontSize: 13, color: Color(0xFF94A3B8)),
              prefixIcon: const Icon(Icons.search_rounded, color: Color(0xFF64748B)),
              suffixIcon: _searchController.text.isNotEmpty
                  ? IconButton(
                      icon: const Icon(Icons.clear_rounded, size: 18),
                      onPressed: () {
                        _searchController.clear();
                        provider.setSearchQuery('', lang);
                      },
                    )
                  : null,
            ),
            onSubmitted: (q) => provider.setSearchQuery(q, lang),
          ),
        ),

        // Topic chips
        if (provider.topics.isNotEmpty)
          SizedBox(
            height: 44,
            child: ListView(
              scrollDirection: Axis.horizontal,
              padding: const EdgeInsets.symmetric(horizontal: 16),
              children: [
                Padding(
                  padding: const EdgeInsets.only(right: 8),
                  child: FilterChip(
                    selected: provider.selectedTopicId == null,
                    label: Text(context.tr('all_topics')),
                    onSelected: (_) => provider.selectTopic(null, lang),
                    selectedColor: const Color(0xFF4F46E5),
                    labelStyle: TextStyle(
                      color: provider.selectedTopicId == null
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
                ...provider.topics.map((t) {
                  final isSelected = provider.selectedTopicId == t.id;
                  return Padding(
                    padding: const EdgeInsets.only(right: 8),
                    child: FilterChip(
                      selected: isSelected,
                      label: Text(t.name),
                      onSelected: (_) => provider.selectTopic(t.id, lang),
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
            child: provider.isLoading
                ? const LoadingIndicator()
                : provider.errorMessage != null
                    ? ErrorStateWidget(
                        message: provider.errorMessage,
                        onRetry: _loadData,
                      )
                    : provider.questions.isEmpty
                        ? Center(
                            child: Text(
                              context.tr('no_questions_found'),
                              style: const TextStyle(
                                color: Color(0xFF64748B),
                                fontSize: 14,
                              ),
                            ),
                          )
                        : ListView.builder(
                            physics: const AlwaysScrollableScrollPhysics(),
                            padding: const EdgeInsets.all(16),
                            itemCount: provider.questions.length,
                            itemBuilder: (context, index) {
                              final item = provider.questions[index];
                              return _buildQuestionCard(context, item);
                            },
                          ),
          ),
        ),
      ],
    );
  }

  Widget _buildQuestionCard(BuildContext context, Question q) {
    return Card(
      margin: const EdgeInsets.only(bottom: 14),
      shape: RoundedRectangleBorder(
        borderRadius: BorderRadius.circular(16),
        side: const BorderSide(color: Color(0xFFE2E8F0)),
      ),
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Row(
              children: [
                if (q.topic != null)
                  Container(
                    padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 3),
                    decoration: BoxDecoration(
                      color: const Color(0xFFE0E7FF),
                      borderRadius: BorderRadius.circular(6),
                    ),
                    child: Text(
                      q.topic!.name,
                      style: const TextStyle(
                        color: Color(0xFF4F46E5),
                        fontSize: 11,
                        fontWeight: FontWeight.w700,
                      ),
                    ),
                  ),
                const Spacer(),
                Container(
                  padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 3),
                  decoration: BoxDecoration(
                    color: const Color(0xFFECFDF5),
                    borderRadius: BorderRadius.circular(6),
                  ),
                  child: Row(
                    mainAxisSize: MainAxisSize.min,
                    children: [
                      const Icon(Icons.check_circle_rounded, size: 12, color: Color(0xFF10B981)),
                      const SizedBox(width: 4),
                      Text(
                        context.tr('status_answered'),
                        style: const TextStyle(
                          color: Color(0xFF047857),
                          fontSize: 11,
                          fontWeight: FontWeight.w700,
                        ),
                      ),
                    ],
                  ),
                ),
              ],
            ),
            const SizedBox(height: 10),
            Text(
              q.subject,
              style: const TextStyle(
                fontSize: 16,
                fontWeight: FontWeight.bold,
                color: Color(0xFF0F172A),
              ),
            ),
            const SizedBox(height: 6),
            Text(
              q.body,
              style: const TextStyle(
                fontSize: 13,
                color: Color(0xFF475569),
                height: 1.4,
              ),
            ),
            const SizedBox(height: 8),
            Text(
              '${q.name} • ${q.createdAt?.split('T').first ?? ''}',
              style: const TextStyle(fontSize: 11, color: Color(0xFF94A3B8)),
            ),

            // Answers Box
            if (q.answers.isNotEmpty) ...[
              const SizedBox(height: 14),
              Container(
                width: double.infinity,
                padding: const EdgeInsets.all(12),
                decoration: BoxDecoration(
                  color: const Color(0xFFF8FAFC),
                  borderRadius: BorderRadius.circular(12),
                  border: Border.all(color: const Color(0xFFE2E8F0)),
                ),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Row(
                      children: [
                        const Icon(
                          Icons.verified_rounded,
                          size: 16,
                          color: Color(0xFF4F46E5),
                        ),
                        const SizedBox(width: 6),
                        Text(
                          context.tr('official_answer'),
                          style: const TextStyle(
                            fontSize: 12,
                            fontWeight: FontWeight.bold,
                            color: Color(0xFF4F46E5),
                          ),
                        ),
                      ],
                    ),
                    const SizedBox(height: 6),
                    Text(
                      q.answers.first.body,
                      style: const TextStyle(
                        fontSize: 13,
                        color: Color(0xFF1E293B),
                        height: 1.4,
                      ),
                    ),
                    const SizedBox(height: 6),
                    Text(
                      '${context.tr('answered_at')} ${q.answers.first.answeredAt?.split('T').first ?? ''}',
                      style: const TextStyle(fontSize: 11, color: Color(0xFF94A3B8)),
                    ),
                  ],
                ),
              ),
            ],
          ],
        ),
      ),
    );
  }

  Widget _buildSubmitFormTab(
    BuildContext context,
    QuestionProvider provider,
  ) {
    return SingleChildScrollView(
      padding: const EdgeInsets.all(20),
      child: Form(
        key: _formKey,
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              context.tr('ask_now'),
              style: const TextStyle(
                fontSize: 18,
                fontWeight: FontWeight.bold,
                color: Color(0xFF0F172A),
              ),
            ),
            const SizedBox(height: 4),
            Text(
              context.tr('ask_question_desc'),
              style: const TextStyle(fontSize: 13, color: Color(0xFF64748B)),
            ),
            const SizedBox(height: 20),

            // Topic Dropdown
            Text(
              context.tr('form_topic_label'),
              style: const TextStyle(
                fontSize: 13,
                fontWeight: FontWeight.w600,
                color: Color(0xFF334155),
              ),
            ),
            const SizedBox(height: 6),
            DropdownButtonFormField<int>(
              initialValue: _selectedTopicId,
              hint: Text(context.tr('form_topic_hint')),
              items: provider.topics.map((t) {
                return DropdownMenuItem<int>(
                  value: t.id,
                  child: Text(t.name),
                );
              }).toList(),
              onChanged: (val) {
                setState(() => _selectedTopicId = val);
              },
              validator: (v) => v == null ? context.tr('required_field') : null,
            ),
            const SizedBox(height: 16),

            // Name Field
            Text(
              context.tr('form_name_label'),
              style: const TextStyle(
                fontSize: 13,
                fontWeight: FontWeight.w600,
                color: Color(0xFF334155),
              ),
            ),
            const SizedBox(height: 6),
            TextFormField(
              controller: _nameController,
              decoration: InputDecoration(hintText: context.tr('form_name_hint')),
              validator: (v) =>
                  (v == null || v.trim().isEmpty) ? context.tr('required_field') : null,
            ),
            const SizedBox(height: 16),

            // Email Field
            Text(
              context.tr('form_email_label'),
              style: const TextStyle(
                fontSize: 13,
                fontWeight: FontWeight.w600,
                color: Color(0xFF334155),
              ),
            ),
            const SizedBox(height: 6),
            TextFormField(
              controller: _emailController,
              keyboardType: TextInputType.emailAddress,
              decoration: InputDecoration(hintText: context.tr('form_email_hint')),
              validator: (v) {
                if (v == null || v.trim().isEmpty) return context.tr('required_field');
                if (!v.contains('@') || !v.contains('.')) return context.tr('invalid_email');
                return null;
              },
            ),
            const SizedBox(height: 16),

            // Phone Field
            Text(
              context.tr('form_phone_label'),
              style: const TextStyle(
                fontSize: 13,
                fontWeight: FontWeight.w600,
                color: Color(0xFF334155),
              ),
            ),
            const SizedBox(height: 6),
            TextFormField(
              controller: _phoneController,
              keyboardType: TextInputType.phone,
              decoration: InputDecoration(hintText: context.tr('form_phone_hint')),
            ),
            const SizedBox(height: 16),

            // Subject Field
            Text(
              context.tr('form_subject_label'),
              style: const TextStyle(
                fontSize: 13,
                fontWeight: FontWeight.w600,
                color: Color(0xFF334155),
              ),
            ),
            const SizedBox(height: 6),
            TextFormField(
              controller: _subjectController,
              decoration: InputDecoration(hintText: context.tr('form_subject_hint')),
              validator: (v) =>
                  (v == null || v.trim().isEmpty) ? context.tr('required_field') : null,
            ),
            const SizedBox(height: 16),

            // Body Field
            Text(
              context.tr('form_body_label'),
              style: const TextStyle(
                fontSize: 13,
                fontWeight: FontWeight.w600,
                color: Color(0xFF334155),
              ),
            ),
            const SizedBox(height: 6),
            TextFormField(
              controller: _bodyController,
              maxLines: 5,
              decoration: InputDecoration(hintText: context.tr('form_body_hint')),
              validator: (v) =>
                  (v == null || v.trim().isEmpty) ? context.tr('required_field') : null,
            ),
            const SizedBox(height: 24),

            // Submit Button
            SizedBox(
              width: double.infinity,
              height: 50,
              child: ElevatedButton.icon(
                onPressed: provider.isSubmitting ? null : _submitForm,
                icon: provider.isSubmitting
                    ? const SizedBox(
                        width: 20,
                        height: 20,
                        child: CircularProgressIndicator(
                          strokeWidth: 2,
                          color: Colors.white,
                        ),
                      )
                    : const Icon(Icons.send_rounded, size: 18),
                label: Text(
                  context.tr('btn_submit_question'),
                  style: const TextStyle(fontSize: 15, fontWeight: FontWeight.bold),
                ),
                style: ElevatedButton.styleFrom(
                  backgroundColor: const Color(0xFF4F46E5),
                  foregroundColor: Colors.white,
                  shape: RoundedRectangleBorder(
                    borderRadius: BorderRadius.circular(12),
                  ),
                ),
              ),
            ),

            const SizedBox(height: 40),
          ],
        ),
      ),
    );
  }
}
