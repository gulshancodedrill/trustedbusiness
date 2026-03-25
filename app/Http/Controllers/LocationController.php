<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\State;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    /**
     * States for a country (dependent dropdown).
     */
    public function states(Request $request): JsonResponse
    {
        $data = $request->validate([
            'country_id' => ['required', 'integer', 'exists:countries,id'],
        ]);

        $states = State::query()
            ->where('country_id', $data['country_id'])
            ->orderBy('name')
            ->get(['id', 'name', 'iso2']);

        return response()->json($states);
    }

    /**
     * Cities for a state (dependent dropdown).
     */
    public function cities(Request $request): JsonResponse
    {
        $data = $request->validate([
            'state_id' => ['required', 'integer', 'exists:states,id'],
        ]);

        $cities = City::query()
            ->where('state_id', $data['state_id'])
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($cities);
    }
}
