<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\governor;
use Illuminate\Http\Request;

class CountriesController extends Controller
{
    public function index()
    {
        $countries = Country::with('governors.cities')->get();

        $data = $countries->map(function ($country) {
            return [
                'id' => $country->id,
                'name_ar' => $country->getTranslation('name', 'ar'),
                'name_en' => $country->getTranslation('name', 'en'),
                'governors' => $country->governors->map(function ($governor) {
                    return [
                        'id' => $governor->id,
                        'name_ar' => $governor->getTranslation('name', 'ar'),
                        'name_en' => $governor->getTranslation('name', 'en'),
                        'cities' => $governor->cities->map(function ($city) {
                            return [
                                'id' => $city->id,
                                'name_ar' => $city->getTranslation('name', 'ar'),
                                'name_en' => $city->getTranslation('name', 'en'),
                            ];
                        }),
                    ];
                }),
            ];
        });

        return response()->json([
            'message' => 'Countries ✔️',
            'data' => $data
        ], 200);
    }
}
