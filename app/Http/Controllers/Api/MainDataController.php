<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PlatformMainData;
use Illuminate\Http\Request;

class MainDataController extends Controller
{
    public function index()
    {
        $main = PlatformMainData::query()->first();

        return response()->json([
            'message' => 'Main data retrieved successfully ✔️',
            'data' => $main,

        ], 200);
    }
}
