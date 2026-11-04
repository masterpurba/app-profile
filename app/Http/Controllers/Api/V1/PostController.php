<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\PostStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PostController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Post::query()
            ->with(['category', 'user'])
            ->where('status', PostStatus::Published)
            ->where('published_at', '<=', now());

        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->query('category'));
            });
        }

        if ($request->filled('featured')) {
            $query->where('is_featured', filter_var($request->query('featured'), FILTER_VALIDATE_BOOLEAN));
        }

        if ($request->filled('search')) {
            $search = $request->query('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('summary', 'like', "%{$search}%");
            });
        }

        $posts = $query->latest('published_at')->paginate($request->integer('per_page', 10));

        return PostResource::collection($posts);
    }

    public function show(string $slug): PostResource
    {
        $post = Post::query()
            ->with(['category', 'user'])
            ->where('status', PostStatus::Published)
            ->where('slug', $slug)
            ->firstOrFail();

        $post->increment('view_count');

        return new PostResource($post);
    }
}
