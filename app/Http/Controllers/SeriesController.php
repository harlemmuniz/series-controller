<?php

namespace App\Http\Controllers;

use App\Episode;
use App\Events\NewSerieCreatedEvent;
use App\Http\Requests\SeriesFormRequest;
use App\Season;
use App\Serie;
use App\Services\SeriesCreator;
use App\Services\SeriesRemover;
use App\User;
use Illuminate\Http\Request;

class SeriesController extends Controller
{
    public function index(Request $request)
    {
        $series = Serie::orderBy('name')->get();
        $message = $request->session()->get('message');
        return view('series.index', compact('series', 'message'));
    }

    public function add()
    {
        return view('series.add');
    }

    public function store(SeriesFormRequest $request, SeriesCreator $seriesCreator)
    {
        $cover = null;
        if ($request->hasFile('cover')) {
            $cover = $request->file('cover')->store('serie');
        }
        
        $serie = $seriesCreator->createSerie(
            $request->name,
            $request->seasons_qty,
            $request->episodes_qty,
            $cover
        );

        $newSerieCreatedEvent = new NewSerieCreatedEvent(
            $request->name,
            $request->seasons_qty,
            $request->episodes_qty
        );

        event($newSerieCreatedEvent);

        $request->session()->flash('message', "Serie $serie->name and its seasons and episodes succesfully created!");

        return redirect()->route('list_series');
    }

    public function editName(int $id, SeriesFormRequest $request)
    {
        $newName = $request->name;
        $serie = Serie::find($id);
        $serie->name = $newName;
        $serie->save();
    }

    public function destroy(Request $request, SeriesRemover $seriesRemover)
    {
        $serieName = $seriesRemover->removeSerie($request->id);
        $request->session()->flash(
            'message',
            "Serie $serieName succesfully removed!"
        );

        return redirect()->route('list_series');
    }
}
