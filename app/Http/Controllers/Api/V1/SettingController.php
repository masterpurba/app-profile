<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\SettingResource;
use App\Models\Setting;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SettingController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $settings = Setting::all();

        return SettingResource::collection($settings);
    }
}
