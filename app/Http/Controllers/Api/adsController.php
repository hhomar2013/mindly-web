<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ads;

class adsController extends Controller
{
    public function index()
    {
        $ads  = ads::query()->where('status', 1)->get();
        $data = [];
        foreach ($ads as $val) {
            $data[] = [
                'type'    => $val->type,
                'from'    => $val->from,
                'image'   => $val->image ? asset('storage/' . $val->image) : null,
                'link'    => $val->link,
                'comment' => $val->comment,
            ];
        }

        return response()->json([
            'message' => 'Ads ✔️',
            'data'    => $data,
        ], 200);
    }
}
