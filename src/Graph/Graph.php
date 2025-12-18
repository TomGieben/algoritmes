<?php

declare(strict_types=1);

namespace Algoritmes\Graph;

use Algoritmes\Graph\Edge;
use Algoritmes\Graph\Interfaces\GraphInterface;
use InvalidArgumentException;

/**
 * Weighted directed graph implementation using adjacency list
 */
class Graph implements GraphInterface
{
    /**
     * Adjacency list representation (vertex => Edge[])
     */
    private array $adjacency = [];

    /**
     * {@inheritdoc}
     */
    public function addVertex(string $vertex): void
    {
        if (!isset($this->adjacency[$vertex])) {
            $this->adjacency[$vertex] = [];
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addEdge(string $from, string $to, float $weight): void
    {
        $this->addVertex($from);
        $this->addVertex($to);

        $this->adjacency[$from][] = new Edge($from, $to, (float)$weight);
    }

    /**
     * {@inheritdoc}
     */
    public function getVertices(): array
    {
        return array_keys($this->adjacency);
    }

    /**
     * {@inheritdoc}
     */
    public function getEdges(string $vertex): array
    {
        if (!isset($this->adjacency[$vertex])) {
            throw new InvalidArgumentException("Vertex '$vertex' does not exist");
        }

        return $this->adjacency[$vertex];
    }

    /**
     * {@inheritdoc}
     */
    public function hasVertex(string $vertex): bool
    {
        return isset($this->adjacency[$vertex]);
    }

    /**
     * {@inheritdoc}
     */
    public function hasEdge(string $from, string $to): bool
    {
        if (!isset($this->adjacency[$from])) {
            return false;
        }

        foreach ($this->adjacency[$from] as $edge) {
            if ($edge->to === $to) {
                return true;
            }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function getVertexCount(): int
    {
        return count($this->adjacency);
    }

    /**
     * {@inheritdoc}
     */
    public function getEdgeCount(): int
    {
        $count = 0;
        foreach ($this->adjacency as $edges) {
            $count += count($edges);
        }
        return $count;
    }
}
