<?php

declare(strict_types=1);

namespace Algoritmes\Graph;

/**
 * Represents an edge in a weighted graph
 */
class Edge
{
    public function __construct(
        public string $from,
        public string $to,
        public float $weight
    ) {
    }
}
