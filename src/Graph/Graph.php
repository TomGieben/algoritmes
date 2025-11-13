<?php

declare(strict_types=1);

namespace Algoritmes\Graph;

use Algoritmes\Graph\Interfaces\GraphInterface;
use InvalidArgumentException;

/**
 * Weighted directed graph implementation using adjacency list
 */
class Graph implements GraphInterface
{
    /**
     * Adjacency list representation
     * vertex => [['to' => vertex, 'weight' => weight], ...]
     */
    private array $adjList = [];

    /**
     * All vertices in the graph
     */
    private array $vertices = [];

    /**
     * {@inheritdoc}
     */
    public function addVertex(string $vertex): void
    {
        if (!isset($this->adjList[$vertex])) {
            $this->vertices[] = $vertex;
            $this->adjList[$vertex] = [];
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addEdge(string $from, string $to, float $weight): void
    {
        if (!isset($this->adjList[$from])) {
            $this->addVertex($from);
        }
        if (!isset($this->adjList[$to])) {
            $this->addVertex($to);
        }

        // Add directed edge
        $this->adjList[$from][] = ['to' => $to, 'weight' => $weight];
    }

    /**
     * {@inheritdoc}
     */
    public function getVertices(): array
    {
        return $this->vertices;
    }

    /**
     * {@inheritdoc}
     */
    public function getEdges(string $vertex): array
    {
        if (!isset($this->adjList[$vertex])) {
            throw new InvalidArgumentException("Vertex '$vertex' does not exist");
        }

        return $this->adjList[$vertex];
    }

    /**
     * {@inheritdoc}
     */
    public function hasVertex(string $vertex): bool
    {
        return isset($this->adjList[$vertex]);
    }

    /**
     * {@inheritdoc}
     */
    public function hasEdge(string $from, string $to): bool
    {
        if (!isset($this->adjList[$from])) {
            return false;
        }

        foreach ($this->adjList[$from] as $edge) {
            if ($edge['to'] === $to) {
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
        return count($this->vertices);
    }

    /**
     * {@inheritdoc}
     */
    public function getEdgeCount(): int
    {
        $count = 0;
        foreach ($this->adjList as $edges) {
            $count += count($edges);
        }
        return $count;
    }
}
