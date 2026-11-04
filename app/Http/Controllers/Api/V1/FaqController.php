<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\FaqResource;
use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class FaqController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Faq::query()
            ->with('topic')
            ->where('is_active', true);

        if ($request->filled('topic_id')) {
            $query->where('topic_id', $request->integer('topic_id'));
        }

        $faqs = $query->orderBy('sort_order')->get();

        return FaqResource::collection($faqs);
    }
}
