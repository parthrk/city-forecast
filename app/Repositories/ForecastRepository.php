<?php

namespace App\Repositories;

use App\Models\Forecast;

class ForecastRepository
{
    public function deleteCityForecasts($cityId)
    {
        return Forecast::where('city_id', $cityId)->delete();
    }

    public function storeForecast($data)
    {
        return Forecast::create($data);
    }
}
