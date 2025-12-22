<?php

namespace Algoritmes\Helpers;

use Algoritmes\Objects\AdjacencyListGraph;
use Algoritmes\Objects\Edge;

final class GraphGenerator
{
    public static function createGridGraph(int $width, int $height): AdjacencyListGraph
    {
        $graph = new AdjacencyListGraph();

        for ($y = 0; $y < $height; $y++) {
            for ($x = 0; $x < $width; $x++) {
                $node = $y * $width + $x;

                // Right
                if ($x + 1 < $width) {
                    $right = $y * $width + ($x + 1);
                    $graph->addEdge($node, $right, 1.0);
                }

                // Down
                if ($y + 1 < $height) {
                    $down = ($y + 1) * $width + $x;
                    $graph->addEdge($node, $down, 1.0);
                }

                // Left
                if ($x - 1 >= 0) {
                    $left = $y * $width + ($x - 1);
                    $graph->addEdge($node, $left, 1.0);
                }

                // Up
                if ($y - 1 >= 0) {
                    $up = ($y - 1) * $width + $x;
                    $graph->addEdge($node, $up, 1.0);
                }
            }
        }

        return $graph;
    }
}
