<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\SlideResource;
use App\Models\Slide;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SlideController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $today = now()->toDateString();

        $slides = Slide::query()
            ->where('is_active', true)
            ->where(function ($q) use ($today) {
                $q->whereNull('start_date')->orWhere('start_date', '<=', $today);
            })
            ->where(function ($q) use ($today) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', $today);
            })
            ->orderBy('sort_order')
            ->get();

        return SlideResource::collection($slides);
    }
}
