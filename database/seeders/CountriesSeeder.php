<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CountriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $country = Country::query()->create([
            'name'=>[
                'ar' =>'مصر',
                'en'=>'Egypr'
            ],
            'code' => 'EG',
            'image'=>'countries/rnWg2hlJKksIMiJr2mXkf1bFB49Ku9pPorjJNKkX.png',
            'status'=>1,

        ]);
    }
}
