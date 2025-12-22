<?php

namespace Algoritmes\Interfaces;

interface IsGraph
{
    public function addEdge(mixed $from, mixed $to, float $weight = 1.0): void;
    public function getNeighbors(mixed $node): IsList;
    public function getWeight(mixed $from, mixed $to): ?float;
}
