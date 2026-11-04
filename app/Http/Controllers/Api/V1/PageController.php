<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\PageStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\PageResource;
use App\Models\Page;

class PageController extends Controller
{
    public function show(string $slug): PageResource
    {
        $page = Page::query()
            ->where('status', PageStatus::Published)
            ->where('slug', $slug)
            ->firstOrFail();

        return new PageResource($page);
    }
}
