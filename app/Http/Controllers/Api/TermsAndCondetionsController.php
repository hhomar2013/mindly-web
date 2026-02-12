<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TermAndCondition;

class TermsAndCondetionsController extends Controller
{
    public function index()
    {
        $terms   = TermAndCondition::current('terms');
        $privacy = TermAndCondition::current('privacy');

        return response()->json([
            'message' => 'Terms and Conditions fetched successfully',
            'data'    => [
                'terms'   => $terms ? [$terms->content] : null,
                'privacy' => $privacy ? [$privacy->content] : null,
            ],
        ]);

    }
}
