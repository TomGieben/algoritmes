<?php

use Algoritmes\Algoritmes\PriorityQueue;

class PriorityQueueBench
{
    private $queue;
    private $size = 1000;

    public function setUp()
    {
        $this->queue = new PriorityQueue();
        // Fill with priorities 0 to 999
        for ($i = 0; $i < $this->size; $i++) {
            $this->queue->enqueue("item_$i", $i);
        }
    }

    /**
     * Best Case: Enqueue item with priority lower than all existing.
     * With binary heap, this is still O(log n) but from root.
     * 
     * @Revs(1000)
     * @Iterations(5)
     * @BeforeMethods({"setUp"})
     */
    public function benchBestCase()
    {
        // Priority -1 is smaller than all, will bubble up from leaf to root
        $this->queue->enqueue("best", -1);
    }

    /**
     * Worst Case: Enqueue item with priority higher than all existing.
     * With binary heap, stays at leaf level (minimal bubbling).
     * 
     * @Revs(1000)
     * @Iterations(5)
     * @BeforeMethods({"setUp"})
     */
    public function benchWorstCase()
    {
        // Priority 2000 is larger than all, minimal heap adjustments
        $this->queue->enqueue("worst", 2000);
    }
}
