<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ads;
use Illuminate\Http\Request;

class adsController extends Controller
{
    public function index()
    {
        $ads = ads::query()->where('status', 1)->get();
        $data = [];
        foreach ($ads as $val) {
            $data[] = [
                'type' => $val->type,
                'image' => $val->image ? asset('storage/' . $val->image) : null,
                'comment' => $val->comment,
            ];
        }

        return response()->json([
            'message' => 'Ads ✔️',
            'data' => $data,
        ], 200);
    }
}
