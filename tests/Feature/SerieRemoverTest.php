<?php

namespace Tests\Feature;

use App\Services\SeriesCreator;
use App\Services\SeriesRemover;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SerieRemoverTest extends TestCase
{
    use RefreshDatabase;

    /** @var Serie */
    private $serie;

    protected function setUp(): void
    {
        parent::setUp();
        $serieCreator = new SeriesCreator();
        $this->serie = $serieCreator->createSerie('Test Serie', 1, 1);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testRemoveSerie()
    {
        $this->assertDatabaseHas('series', ['id' => $this->serie->id]);
        $serieRemover = new SeriesRemover();
        $serieId = $this->serie->id;
        $serieName = $serieRemover->removeSerie($serieId);
        $this->assertIsString($serieName);
        $this->assertEquals('Test Serie', $this->serie->name);
        $this->assertDatabaseMissing('series', ['id' => $this->serie->id]);
    }
}
