<?php

declare(strict_types=1);

namespace Algoritmes\Graph;

use Algoritmes\Graph\Interfaces\GraphInterface;
use Algoritmes\Graph\Interfaces\PathfinderInterface;
use InvalidArgumentException;

/**
 * Dijkstra's Algorithm for finding shortest paths
 * 
 * Time Complexity:
 * - With binary heap: O((V + E) log V)
 * - With array: O(V²) in worst case
 * 
 * Space Complexity: O(V)
 * 
 * Note: Only works with non-negative edge weights!
 * For graphs with negative edges, use Bellman-Ford algorithm
 */
class Dijkstra implements PathfinderInterface
{
    private GraphInterface $graph;

    public function __construct(GraphInterface $graph)
    {
        $this->graph = $graph;
    }

    /**
     * {@inheritdoc}
     */
    public function findPath(string $start, string $end): array
    {
        if (!$this->graph->hasVertex($start)) {
            throw new InvalidArgumentException("Start vertex '$start' does not exist");
        }
        if (!$this->graph->hasVertex($end)) {
            throw new InvalidArgumentException("End vertex '$end' does not exist");
        }

        $distances = $this->findDistances($start);
        $path = $this->reconstructPath($start, $end, $distances);

        return [
            'path' => $path,
            'distance' => $distances[$end] ?? PHP_FLOAT_MAX
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function findDistances(string $start): array
    {
        if (!$this->graph->hasVertex($start)) {
            throw new InvalidArgumentException("Start vertex '$start' does not exist");
        }

        $vertices = $this->graph->getVertices();
        
        // Initialize distances and previous vertices tracking
        $distances = [];
        $previous = [];
        $visited = [];

        foreach ($vertices as $vertex) {
            $distances[$vertex] = PHP_FLOAT_MAX;
            $previous[$vertex] = null;
            $visited[$vertex] = false;
        }

        // Start vertex has distance 0
        $distances[$start] = 0;

        // Process all vertices
        for ($i = 0; $i < count($vertices); $i++) {
            // Find unvisited vertex with minimum distance
            $currentVertex = $this->findMinDistanceVertex($distances, $visited);

            if ($currentVertex === null || $distances[$currentVertex] === PHP_FLOAT_MAX) {
                break; // No more reachable vertices
            }

            $visited[$currentVertex] = true;

            // Update distances to neighbors
            $edges = $this->graph->getEdges($currentVertex);
            foreach ($edges as $edge) {
                $neighbor = $edge['to'];
                $weight = $edge['weight'];

                if (!$visited[$neighbor]) {
                    $newDistance = $distances[$currentVertex] + $weight;
                    
                    if ($newDistance < $distances[$neighbor]) {
                        $distances[$neighbor] = $newDistance;
                        $previous[$neighbor] = $currentVertex;
                    }
                }
            }
        }

        // Store previous for path reconstruction
        $this->previousVertices = $previous;

        return $distances;
    }

    /**
     * Find the unvisited vertex with minimum distance
     */
    private function findMinDistanceVertex(array $distances, array $visited): ?string
    {
        $minDistance = PHP_FLOAT_MAX;
        $minVertex = null;

        foreach ($distances as $vertex => $distance) {
            if (!$visited[$vertex] && $distance < $minDistance) {
                $minDistance = $distance;
                $minVertex = $vertex;
            }
        }

        return $minVertex;
    }

    /**
     * Store previous vertices for path reconstruction
     */
    private array $previousVertices = [];

    /**
     * Reconstruct path from start to end using previous vertices
     */
    private function reconstructPath(string $start, string $end, array $distances): array
    {
        // Run Dijkstra to populate previousVertices if not already done
        if (empty($this->previousVertices)) {
            $this->findDistances($start);
        }

        // Check if path exists
        if ($distances[$end] === PHP_FLOAT_MAX) {
            return []; // No path exists
        }

        $path = [];
        $current = $end;

        while ($current !== null) {
            array_unshift($path, $current);
            $current = $this->previousVertices[$current] ?? null;
        }

        return $path;
    }

    /**
     * Get shortest distances from a start vertex to all vertices
     * Returns array with detailed information
     */
    public function getDetailedDistances(string $start): array
    {
        $distances = $this->findDistances($start);
        $result = [];

        foreach ($distances as $vertex => $distance) {
            $result[$vertex] = [
                'distance' => $distance === PHP_FLOAT_MAX ? 'unreachable' : $distance,
                'reachable' => $distance !== PHP_FLOAT_MAX
            ];
        }

        return $result;
    }

    /**
     * Get all shortest paths from start vertex
     */
    public function getAllPaths(string $start): array
    {
        if (!$this->graph->hasVertex($start)) {
            throw new InvalidArgumentException("Start vertex '$start' does not exist");
        }

        $distances = $this->findDistances($start);
        $paths = [];

        foreach ($this->graph->getVertices() as $vertex) {
            if ($vertex !== $start) {
                $path = $this->reconstructPath($start, $vertex, $distances);
                $paths[$vertex] = [
                    'path' => $path,
                    'distance' => $distances[$vertex] === PHP_FLOAT_MAX ? null : $distances[$vertex]
                ];
            }
        }

        return $paths;
    }
}
