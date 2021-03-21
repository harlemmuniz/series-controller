<?php

namespace App\Services;

use App\{Serie, Season, Episode};
use App\Events\SerieRemovedEvent;
use App\Jobs\RemoveSerieCover;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class SeriesRemover
{
    public function removeSerie(int $serieId): string
    {
        DB::beginTransaction();
        $serie = Serie::find($serieId);
        $serieObj = (object) $serie->toArray();

        $serieName = $serie->name;

        $this->removeSeasons($serie);
        $serie->delete();

        /*$event = new SerieRemovedEvent($serieObj);
        event($event);*/

        RemoveSerieCover::dispatch($serieObj);
        DB::commit();

        return $serieName;
    }

    private function removeSeasons(Serie $serie): void
    {
        $serie->seasons->each(function (Season $season) {
            $this->removeEpisodes($season);
            $season->delete();
        });
    }

    private function removeEpisodes(Season $season): void
    {
        $season->episodes->each(function (Episode $episode) {
            $episode->delete();
        });
    }
}
