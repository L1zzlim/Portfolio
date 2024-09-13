<?php

namespace App\Http\Controllers;

use App\Models\FiltLocations;
use Illuminate\Http\Request;

class FiltLocationsController extends Controller
{
    protected $table = 'FiltLocations';
    public function index()
    {
        $locations = FiltLocations::all();
        return response()->json($locations);
    }
}