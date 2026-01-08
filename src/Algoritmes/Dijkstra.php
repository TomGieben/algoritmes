<?php

namespace Algoritmes\Algoritmes;

use Algoritmes\Interfaces\IsShortestPathFinder;
use Algoritmes\Interfaces\IsGraph;
use Algoritmes\Algoritmes\PriorityQueue;
use Algoritmes\Algoritmes\LinkedList;
use Algoritmes\Algoritmes\HashTable;

final class Dijkstra implements IsShortestPathFinder
{
    public function findShortestPath(IsGraph $graph, mixed $start, mixed $end): ?array
    {
        $distances = new HashTable();  // Afstanden van start naar elke node
        $previous = new HashTable();   // Vorige node in kortste pad
        $pq = new PriorityQueue();     // Te bezoeken nodes (min-heap)

        // Start met afstand 0 naar startnode
        $distances->set($start, 0.0);
        $pq->enqueue($start, 0.0);

        while ($pq->size() > 0) {
            $u = $pq->dequeue();  // Node met kleinste afstand

            if ($u === $end) {
                break;  // Eindnode bereikt
            }

            // Bekijk alle buren van huidige node
            $neighbors = $graph->getNeighbors($u);
            for ($i = 0; $i < $neighbors->size(); $i++) {
                $v = $neighbors->get($i); // Buur node
                $weight = $graph->getWeight($u, $v);
                if ($weight === null) continue;

                // Bereken afstand via huidige node
                $alt = ($distances->has($u) ? $distances->get($u) : PHP_FLOAT_MAX) + $weight;
                $currentDist = $distances->has($v) ? $distances->get($v) : PHP_FLOAT_MAX;

                // Update als korter pad gevonden
                if ($alt < $currentDist) {
                    $distances->set($v, $alt);
                    $previous->set($v, $u);
                    $pq->enqueue($v, $alt);
                }
            }
        }

        if (!$distances->has($end) || $distances->get($end) === PHP_FLOAT_MAX) {
            return null;  // Geen pad gevonden
        }

        // Reconstrueer pad van end naar start
        $path = new LinkedList();
        $current = $end;

        while ($current !== null) {
            $path->insert(0, $current);  // Voeg toe aan begin
            $current = $previous->has($current) ? $previous->get($current) : null;
        }

        if ($path->size() == 0 || $path->get(0) !== $start) {
            return null;
        }

        return ['path' => $path, 'distance' => $distances->get($end)];
    }

    public function findPath(IsGraph $graph, mixed $start, mixed $end): array
    {
        $result = $this->findShortestPath($graph, $start, $end);

        if ($result === null) {
            return ['path' => [], 'distance' => PHP_FLOAT_MAX];
        }

        $path = [];

        for ($i = 0; $i < $result['path']->size(); $i++) {
            $path[] = $result['path']->get($i);
        }

        return ['path' => $path, 'distance' => $result['distance']];
    }
}
