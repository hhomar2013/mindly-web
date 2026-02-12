<?php
namespace App\Helpers;

use App\Models\PlatformMainData;

trait GetMainData
{
    public function getMainData()
    {
        $settings = PlatformMainData::query()->first();
        if ($settings) {
            $data = [
                'name'    => $settings->name,
                'address' => $settings->address,
                'phone'   => $settings->phone,
                'email'   => $settings->email,
            ];
            return $data;
        }

    }
}
