<?php

declare(strict_types=1);

namespace Algoritmes\Graph\Interfaces;

interface GraphInterface
{
    /**
     * Add a vertex to the graph
     */
    public function addVertex(string $vertex): void;

    /**
     * Add a weighted edge to the graph
     */
    public function addEdge(string $from, string $to, float $weight): void;

    /**
     * Get all vertices
     */
    public function getVertices(): array;

    /**
     * Get all edges from a vertex
     */
    public function getEdges(string $vertex): array;

    /**
     * Check if vertex exists
     */
    public function hasVertex(string $vertex): bool;

    /**
     * Check if edge exists between two vertices
     */
    public function hasEdge(string $from, string $to): bool;

    /**
     * Get number of vertices
     */
    public function getVertexCount(): int;

    /**
     * Get number of edges
     */
    public function getEdgeCount(): int;
}
