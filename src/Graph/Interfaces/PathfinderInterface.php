<?php

declare(strict_types=1);

namespace Algoritmes\Graph\Interfaces;

interface PathfinderInterface
{
    /**
     * Find shortest path between two vertices
     * 
     * @param string $start The starting vertex
     * @param string $end The ending vertex
     * @return array Array with 'path' (array of vertices) and 'distance' (total weight)
     */
    public function findPath(string $start, string $end): array;

    /**
     * Find shortest distances from start vertex to all other vertices
     * 
     * @param string $start The starting vertex
     * @return array Array with vertex => distance mapping
     */
    public function findDistances(string $start): array;
}
