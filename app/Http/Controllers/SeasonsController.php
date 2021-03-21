<?php

namespace App\Http\Controllers;

use App\Episode;
use App\Serie;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class SeasonsController extends Controller
{
    public function index(int $serieId) 
    {
        $serie = Serie::find($serieId);
        $seasons = $serie->seasons;
        
        return view('seasons.index', compact('serie', 'seasons'));
    }

}
