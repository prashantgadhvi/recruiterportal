<?php

namespace App\Http\Controllers\API;

use App\City;
use App\Country;
use App\Http\Controllers\Controller;
use App\State;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    public function getCountry()
    {
        return response(['data' => Country::all()]);
    }

    public function getState(int $countryId)
    {
        return response(['data' => State::where('country_id', $countryId)->get()]);
    }

    public function getCity(int $stateId)
    {
        return response(['data' => City::where('state_id', $stateId)->get()]);
    }
}
