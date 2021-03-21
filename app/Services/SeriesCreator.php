<?php

namespace App\Services;

use App\Season;
use App\Serie;
use Illuminate\Support\Facades\DB;

class SeriesCreator
{
    public function createSerie(string $serieName, int $seasonsQty, int $episodesQty, ?string $cover): Serie
    {
        DB::beginTransaction();
        $serie = Serie::create([
            'name' => $serieName,
            'cover' => $cover
            ]);
        $this->createSeasons($serie, $seasonsQty, $episodesQty);
        DB::commit();
        
        return $serie;
    }

    private function createSeasons(Serie $serie, int $seasonsQty, int $episodesQty): void
    {
        for ($i = 1; $i <= $seasonsQty; $i++) {
            $season = $serie->seasons()->create(['number' => $i]);
            $this->createEpisodes($season, $episodesQty);
        }
    }

    private function createEpisodes(Season $season, $episodesQty): void
    {
        for ($i = 1; $i <= $episodesQty; $i++) {
            $season->episodes()->create(['number' => $i]);
        }
    }
}
