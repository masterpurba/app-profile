<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\MenuResource;
use App\Models\Menu;
use Illuminate\Http\JsonResponse;

class MenuController extends Controller
{
    public function show(string $location): JsonResponse|MenuResource
    {
        $menu = Menu::query()
            ->where('location', $location)
            ->where('is_active', true)
            ->with(['items' => function ($q) {
                $q->where('is_active', true)->orderBy('sort_order');
            }])
            ->first();

        if (! $menu) {
            return response()->json([
                'message' => "Menu untuk lokasi '{$location}' tidak ditemukan.",
            ], 404);
        }

        return new MenuResource($menu);
    }
}
