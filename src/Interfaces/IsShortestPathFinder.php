<?php

namespace Algoritmes\Interfaces;

interface IsShortestPathFinder
{
    public function findShortestPath(IsGraph $graph, mixed $start, mixed $end): ?array;
}
