<?php

use Algoritmes\Algoritmes\Dijkstra;
use Algoritmes\Objects\AdjacencyListGraph;

class DijkstraBench
{
    private $graph;
    private $dijkstra;
    private $startNode = "A";
    private $endNode = "Z";
    private $nodes = 50;

    public function setUp()
    {
        $this->graph = new AdjacencyListGraph();
        $this->dijkstra = new Dijkstra();

        // Create a chain graph A -> B -> C ... -> Z
        // This forces Dijkstra to visit many nodes if we ask for path from start to end.

        // Generate nodes
        $nodeNames = [];
        for ($i = 0; $i < $this->nodes; $i++) {
            $nodeNames[] = "Node_$i";
        }
        $this->startNode = $nodeNames[0];
        $this->endNode = $nodeNames[$this->nodes - 1];

        // Connect them linearly: 0->1->2...
        for ($i = 0; $i < $this->nodes - 1; $i++) {
            $this->graph->addEdge($nodeNames[$i], $nodeNames[$i + 1], 1.0);
        }
    }

    /**
     * Best Case: Start node is End node (or very close).
     * 
     * @Revs(100)
     * @Iterations(5)
     * @BeforeMethods({"setUp"})
     */
    public function benchBestCase()
    {
        $this->dijkstra->findShortestPath($this->graph, $this->startNode, $this->startNode);
    }

    /**
     * Worst Case: Path is at the end of the chain (must visit all nodes).
     * 
     * @Revs(100)
     * @Iterations(5)
     * @BeforeMethods({"setUp"})
     */
    public function benchWorstCase()
    {
        $this->dijkstra->findShortestPath($this->graph, $this->startNode, $this->endNode);
    }
}
