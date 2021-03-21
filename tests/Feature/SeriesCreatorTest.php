<?php

namespace Tests\Feature;

use App\Serie;
use App\Services\SeriesCreator;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SeriesCreatorTest extends TestCase
{

    use RefreshDatabase;
    
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCreateSerie()
    {
        $serieCreator = new SeriesCreator();
        $serieName = 'Test Name';
        $seasonsQty = 1;
        $episodesQty = 1;
        $createdSerie = $serieCreator->createSerie($serieName, $seasonsQty, $episodesQty);
        $this->assertInstanceOf(Serie::class, $createdSerie);
        $this->assertDatabaseHas('series', ['name' => $serieName]);
        $this->assertDatabaseHas('seasons', ['serie_id' => $createdSerie->id, 'number' => 1]);
        $this->assertDatabaseHas('episodes', ['number' => 1]);
    }
}
