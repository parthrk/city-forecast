<?php

namespace App\Repositories;

use App\Models\City;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CityRepository
{
    public function getCity($cityId)
    {
        return City::where('weather_city_id', $cityId)->first();
    }

    public function storeCity($data)
    {
        return City::create($data);
    }
}
