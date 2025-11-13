<?php

declare(strict_types=1);

namespace Algoritmes\Tests\Graph;

use PHPUnit\Framework\TestCase;
use Algoritmes\Graph\Graph;
use Algoritmes\Graph\Dijkstra;

class DijkstraTest extends TestCase
{
    private Graph $graph;
    private Dijkstra $dijkstra;

    protected function setUp(): void
    {
        $this->graph = new Graph();
        $this->dijkstra = new Dijkstra($this->graph);
    }

    /**
     * Test simple path
     */
    public function testSimplePath(): void
    {
        $this->graph->addVertex('A');
        $this->graph->addVertex('B');
        $this->graph->addEdge('A', 'B', 5);

        $result = $this->dijkstra->findPath('A', 'B');
        $this->assertEquals(['A', 'B'], $result['path']);
        $this->assertEquals(5, $result['distance']);
    }

    /**
     * Test path with multiple edges
     */
    public function testPathWithMultipleEdges(): void
    {
        $this->graph->addVertex('A');
        $this->graph->addVertex('B');
        $this->graph->addVertex('C');

        $this->graph->addEdge('A', 'B', 4);
        $this->graph->addEdge('B', 'C', 2);
        $this->graph->addEdge('A', 'C', 7);

        $result = $this->dijkstra->findPath('A', 'C');
        $this->assertEquals(['A', 'B', 'C'], $result['path']);
        $this->assertEquals(6, $result['distance']);
    }

    /**
     * Test path selection - shortest path instead of direct
     */
    public function testShortestPathSelection(): void
    {
        $this->graph->addVertex('A');
        $this->graph->addVertex('B');
        $this->graph->addVertex('C');
        $this->graph->addVertex('D');

        $this->graph->addEdge('A', 'B', 1);
        $this->graph->addEdge('B', 'C', 1);
        $this->graph->addEdge('C', 'D', 1);
        $this->graph->addEdge('A', 'D', 10);

        $result = $this->dijkstra->findPath('A', 'D');
        $this->assertEquals(['A', 'B', 'C', 'D'], $result['path']);
        $this->assertEquals(3, $result['distance']);
    }

    /**
     * Test unreachable vertex
     */
    public function testUnreachableVertex(): void
    {
        $this->graph->addVertex('A');
        $this->graph->addVertex('B');
        $this->graph->addVertex('C');

        $this->graph->addEdge('A', 'B', 1);

        $result = $this->dijkstra->findPath('A', 'C');
        $this->assertEquals([], $result['path']);
        $this->assertEquals(PHP_FLOAT_MAX, $result['distance']);
    }

    /**
     * Test start to same vertex
     */
    public function testStartToSameVertex(): void
    {
        $this->graph->addVertex('A');
        
        $result = $this->dijkstra->findPath('A', 'A');
        $this->assertEquals(['A'], $result['path']);
        $this->assertEquals(0, $result['distance']);
    }

    /**
     * Test complex network
     */
    public function testComplexNetwork(): void
    {
        // Create a more complex graph
        $vertices = ['A', 'B', 'C', 'D', 'E', 'F'];
        foreach ($vertices as $v) {
            $this->graph->addVertex($v);
        }

        $this->graph->addEdge('A', 'B', 4);
        $this->graph->addEdge('A', 'C', 2);
        $this->graph->addEdge('B', 'C', 1);
        $this->graph->addEdge('B', 'D', 5);
        $this->graph->addEdge('C', 'D', 8);
        $this->graph->addEdge('C', 'E', 10);
        $this->graph->addEdge('D', 'E', 2);
        $this->graph->addEdge('D', 'F', 6);
        $this->graph->addEdge('E', 'F', 3);

        $result = $this->dijkstra->findPath('A', 'F');
        // Path: A -> B (4) -> D (5) -> E (2) -> F (3) = 14
        $this->assertEquals(['A', 'B', 'D', 'E', 'F'], $result['path']);
        $this->assertEquals(14, $result['distance']);
    }

    /**
     * Test find all distances from start
     */
    public function testFindAllDistances(): void
    {
        $this->graph->addVertex('A');
        $this->graph->addVertex('B');
        $this->graph->addVertex('C');
        $this->graph->addVertex('D');

        $this->graph->addEdge('A', 'B', 1);
        $this->graph->addEdge('A', 'C', 4);
        $this->graph->addEdge('B', 'C', 2);
        $this->graph->addEdge('C', 'D', 1);

        $distances = $this->dijkstra->findDistances('A');

        $this->assertEquals(0, $distances['A']);
        $this->assertEquals(1, $distances['B']);
        $this->assertEquals(3, $distances['C']);
        $this->assertEquals(4, $distances['D']);
    }

    /**
     * Test with floating point weights
     */
    public function testFloatingPointWeights(): void
    {
        $this->graph->addVertex('A');
        $this->graph->addVertex('B');
        $this->graph->addVertex('C');

        $this->graph->addEdge('A', 'B', 1.5);
        $this->graph->addEdge('B', 'C', 2.5);
        $this->graph->addEdge('A', 'C', 5.0);

        $result = $this->dijkstra->findPath('A', 'C');
        $this->assertEquals(['A', 'B', 'C'], $result['path']);
        $this->assertEquals(4.0, $result['distance']);
    }

    /**
     * Test single vertex graph
     */
    public function testSingleVertexGraph(): void
    {
        $this->graph->addVertex('A');

        $result = $this->dijkstra->findPath('A', 'A');
        $this->assertEquals(['A'], $result['path']);
        $this->assertEquals(0, $result['distance']);
    }

    /**
     * Test with many vertices
     */
    public function testLargeGraph(): void
    {
        // Create a chain graph: A -> B -> C -> ... -> Z
        $vertices = [];
        for ($i = 0; $i < 26; $i++) {
            $vertices[] = chr(65 + $i); // A, B, C, ..., Z
        }

        foreach ($vertices as $v) {
            $this->graph->addVertex($v);
        }

        for ($i = 0; $i < count($vertices) - 1; $i++) {
            $this->graph->addEdge($vertices[$i], $vertices[$i + 1], 1);
        }

        $result = $this->dijkstra->findPath('A', 'Z');
        $this->assertEquals(25, $result['distance']);
        $this->assertEquals(26, count($result['path']));
        $this->assertEquals('A', $result['path'][0]);
        $this->assertEquals('Z', $result['path'][25]);
    }

    /**
     * Test get detailed distances
     */
    public function testDetailedDistances(): void
    {
        $this->graph->addVertex('A');
        $this->graph->addVertex('B');
        $this->graph->addVertex('C');
        $this->graph->addVertex('D');

        $this->graph->addEdge('A', 'B', 1);
        $this->graph->addEdge('A', 'C', 4);
        $this->graph->addEdge('B', 'C', 2);

        $detailed = $this->dijkstra->getDetailedDistances('A');

        $this->assertTrue($detailed['A']['reachable']);
        $this->assertEquals(0, $detailed['A']['distance']);
        
        $this->assertTrue($detailed['B']['reachable']);
        $this->assertEquals(1, $detailed['B']['distance']);
        
        $this->assertTrue($detailed['C']['reachable']);
        $this->assertEquals(3, $detailed['C']['distance']);
        
        $this->assertFalse($detailed['D']['reachable']);
        $this->assertEquals('unreachable', $detailed['D']['distance']);
    }

    /**
     * Test get all paths
     */
    public function testGetAllPaths(): void
    {
        $this->graph->addVertex('A');
        $this->graph->addVertex('B');
        $this->graph->addVertex('C');

        $this->graph->addEdge('A', 'B', 1);
        $this->graph->addEdge('A', 'C', 4);
        $this->graph->addEdge('B', 'C', 2);

        $paths = $this->dijkstra->getAllPaths('A');

        $this->assertArrayHasKey('B', $paths);
        $this->assertArrayHasKey('C', $paths);
        
        $this->assertEquals(['A', 'B'], $paths['B']['path']);
        $this->assertEquals(1, $paths['B']['distance']);
        
        $this->assertEquals(['A', 'B', 'C'], $paths['C']['path']);
        $this->assertEquals(3, $paths['C']['distance']);
    }

    /**
     * Test diamond shaped graph
     */
    public function testDiamondGraph(): void
    {
        $this->graph->addVertex('A');
        $this->graph->addVertex('B');
        $this->graph->addVertex('C');
        $this->graph->addVertex('D');

        $this->graph->addEdge('A', 'B', 1);
        $this->graph->addEdge('A', 'C', 4);
        $this->graph->addEdge('B', 'D', 5);
        $this->graph->addEdge('C', 'D', 1);

        $result = $this->dijkstra->findPath('A', 'D');
        $this->assertEquals(['A', 'C', 'D'], $result['path']);
        $this->assertEquals(5, $result['distance']);
    }

    /**
     * Test invalid start vertex
     */
    public function testInvalidStartVertex(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->dijkstra->findPath('X', 'Y');
    }

    /**
     * Test invalid end vertex
     */
    public function testInvalidEndVertex(): void
    {
        $this->graph->addVertex('A');
        
        $this->expectException(\InvalidArgumentException::class);
        $this->dijkstra->findPath('A', 'X');
    }

    /**
     * Test multiple paths same length
     */
    public function testMultiplePathsSameLength(): void
    {
        $this->graph->addVertex('A');
        $this->graph->addVertex('B');
        $this->graph->addVertex('C');
        $this->graph->addVertex('D');

        $this->graph->addEdge('A', 'B', 1);
        $this->graph->addEdge('A', 'C', 1);
        $this->graph->addEdge('B', 'D', 1);
        $this->graph->addEdge('C', 'D', 1);

        $result = $this->dijkstra->findPath('A', 'D');
        $this->assertEquals(2, $result['distance']);
        // One of the paths should be found
        $this->assertTrue(
            $result['path'] === ['A', 'B', 'D'] || 
            $result['path'] === ['A', 'C', 'D']
        );
    }

    /**
     * Test graph with cycles
     */
    public function testGraphWithCycles(): void
    {
        $this->graph->addVertex('A');
        $this->graph->addVertex('B');
        $this->graph->addVertex('C');

        $this->graph->addEdge('A', 'B', 1);
        $this->graph->addEdge('B', 'C', 2);
        $this->graph->addEdge('C', 'A', 3);

        $result = $this->dijkstra->findPath('A', 'C');
        $this->assertEquals(['A', 'B', 'C'], $result['path']);
        $this->assertEquals(3, $result['distance']);
    }

    /**
     * Test with zero weight edges
     */
    public function testZeroWeightEdges(): void
    {
        $this->graph->addVertex('A');
        $this->graph->addVertex('B');
        $this->graph->addVertex('C');

        $this->graph->addEdge('A', 'B', 0);
        $this->graph->addEdge('B', 'C', 5);

        $result = $this->dijkstra->findPath('A', 'C');
        $this->assertEquals(['A', 'B', 'C'], $result['path']);
        $this->assertEquals(5, $result['distance']);
    }

    /**
     * Test graph structure
     */
    public function testGraphStructure(): void
    {
        $this->graph->addVertex('A');
        $this->graph->addVertex('B');
        $this->graph->addEdge('A', 'B', 10);

        $this->assertTrue($this->graph->hasVertex('A'));
        $this->assertTrue($this->graph->hasVertex('B'));
        $this->assertTrue($this->graph->hasEdge('A', 'B'));
        $this->assertFalse($this->graph->hasEdge('B', 'A'));
        
        $this->assertEquals(2, $this->graph->getVertexCount());
        $this->assertEquals(1, $this->graph->getEdgeCount());
    }
}
