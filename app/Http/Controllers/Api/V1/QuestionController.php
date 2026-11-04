<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\QuestionStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\QuestionResource;
use App\Models\Question;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class QuestionController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Question::query()
            ->with(['topic', 'answers' => function ($q) {
                $q->where('is_published', true)->with('user');
            }])
            ->where('status', QuestionStatus::Answered);

        if ($request->filled('topic_id')) {
            $query->where('topic_id', $request->integer('topic_id'));
        }

        if ($request->filled('search')) {
            $search = $request->query('search');
            $query->where(function ($q) use ($search) {
                $q->where('subject', 'like', "%{$search}%")
                    ->orWhere('body', 'like', "%{$search}%");
            });
        }

        $questions = $query->latest()->paginate($request->integer('per_page', 10));

        return QuestionResource::collection($questions);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'topic_id' => 'nullable|exists:question_topics,id',
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:150',
            'phone' => 'nullable|string|max:30',
            'subject' => 'required|string|max:200',
            'body' => 'required|string|min:10',
        ]);

        $validated['status'] = QuestionStatus::Pending;
        $validated['ip_address'] = $request->ip();

        $question = Question::create($validated);

        return response()->json([
            'message' => 'Pertanyaan Anda berhasil dikirim dan sedang menunggu tanggapan petugas.',
            'data' => new QuestionResource($question->load('topic')),
        ], 201);
    }
}
