<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddCityRequest;
use App\Models\Forecast;
use App\Repositories\CityRepository;
use App\Repositories\ForecastRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use DataTables;
use Illuminate\Support\Facades\DB;

class ForecastController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        if ($request->ajax()) {
            $model = Forecast::with('city')->select('*');
            return Datatables::eloquent($model)
                    ->addIndexColumn()
                    ->addColumn('city', function (Forecast $forecast) {
                        return $forecast->city->name;
                    })
                    ->toJson();
        }
          
        return view('forecasts');
    }

    public function addCity(AddCityRequest $request) {
        $cityName = $request->name;

        $endpoint = env('WEATHER_API_BASEURL');
        $apiKey = env('WEATHER_API_KEY');

        $response = Http::get($endpoint, [
            'q' => $cityName, 
            'units' => 'metric', 
            'appid' => $apiKey,
        ]);

        if($response->successful()) {
            // Save city if not already exists in database
            $cityData = $response->json('city');
            $city = app(CityRepository::class)->getCity($cityData['id']);
            if(!empty($city)) {
                app(ForecastRepository::class)->deleteCityForecasts($city->id);
            } else {
                $city = app(CityRepository::class)->storeCity([
                    'name' => $cityName,
                    'weather_city_id' => $cityData['id']
                ]);
            }
            
            // Save 5 day / 3 hour forecast data for city
            $forecastData = $response->json('list');
            foreach($forecastData as $forecast) {
                app(ForecastRepository::class)->storeForecast([
                    'city_id' => $city->id,
                    'forecast_datetime' => $forecast['dt_txt'],
                    'temperature' => $forecast['main']['temp'],
                    'humidity' => $forecast['main']['humidity'],
                    'weather_main' => $forecast['weather'][0]['main'],
                    'weather_description' => $forecast['weather'][0]['description']
                ]);
            }

            return response(['status' => 'success'], 201);
        } else {
            return response(['status' => 'fail'], 500);
        }
    }
}
