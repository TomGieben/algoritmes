<?php

namespace Algoritmes\Objects;

use Algoritmes\Interfaces\IsGraph;
use Algoritmes\Interfaces\IsList;
use Algoritmes\Algoritmes\HashTable;
use Algoritmes\Algoritmes\LinkedList;

class AdjacencyListGraph implements IsGraph
{
    private HashTable $adjacencyMap;

    public function __construct()
    {
        $this->adjacencyMap = new HashTable();
    }

    public function addEdge(mixed $from, mixed $to, float $weight = 1.0): void
    {
        if (!$this->adjacencyMap->has($from)) {
            $this->adjacencyMap->set($from, new LinkedList());
        }
        $list = $this->adjacencyMap->get($from);
        $list->add(new Edge($from, $to, $weight));
    }

    public function getNeighbors(mixed $node): IsList
    {
        if (!$this->adjacencyMap->has($node)) {
            return new LinkedList();
        }
        $edges = $this->adjacencyMap->get($node);
        $neighbors = new LinkedList();
        for ($i = 0; $i < $edges->size(); $i++) {
            $edge = $edges->get($i);
            $neighbors->add($edge->getTo());
        }
        return $neighbors;
    }

    public function getWeight(mixed $from, mixed $to): ?float
    {
        if (!$this->adjacencyMap->has($from)) {
            return null;
        }
        $edges = $this->adjacencyMap->get($from);
        for ($i = 0; $i < $edges->size(); $i++) {
            $edge = $edges->get($i);
            if ($edge->getTo() === $to) {
                return $edge->getWeight();
            }
        }
        return null;
    }
}
