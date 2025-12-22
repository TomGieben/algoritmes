<?php

namespace Tests\Algoritmes;

use PHPUnit\Framework\TestCase;
use Algoritmes\Algoritmes\Dijkstra;
use Algoritmes\Helpers\GraphGenerator;

class DijkstraTest extends TestCase
{
    private Dijkstra $dijkstra;
    private $graph;

    protected function setUp(): void
    {
        $this->dijkstra = new Dijkstra();
        // Create a 100x100 grid graph for large dataset
        $this->graph = GraphGenerator::createGridGraph(100, 100);
    }

    public function testBestCase(): void
    {
        // Best case: direct path, start and end are adjacent
        $start = 0;
        $end = 1; // Adjacent in grid
        $result = $this->dijkstra->findShortestPath($this->graph, $start, $end);
        $this->assertNotNull($result);
        $this->assertEquals(1.0, $result['distance']);
        $this->assertEquals(2, $result['path']->size()); // start and end
    }

    public function testWorstCase(): void
    {
        // Worst case: long path, from top-left to bottom-right
        $start = 0;
        $end = 9999; // 100*100 - 1
        $result = $this->dijkstra->findShortestPath($this->graph, $start, $end);
        $this->assertNotNull($result);
        // Manhattan distance: 99 + 99 = 198
        $this->assertEquals(198.0, $result['distance']);
        $this->assertGreaterThan(2, $result['path']->size());
    }
}
