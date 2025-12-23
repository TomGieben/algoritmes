<?php

use Algoritmes\Algoritmes\PriorityQueue;
use Algoritmes\Algoritmes\LinkedList;

class PriorityQueueBench
{
    private $queue;
    private $size = 1000;

    public function setUp()
    {
        $this->queue = new PriorityQueue(new LinkedList());
        // Fill with priorities 0 to 999
        for ($i = 0; $i < $this->size; $i++) {
            $this->queue->enqueue("item_$i", $i);
        }
    }

    /**
     * Best Case: Enqueue item with priority lower than all existing (inserted at start).
     * 
     * @Revs(1000)
     * @Iterations(5)
     * @BeforeMethods({"setUp"})
     */
    public function benchBestCase()
    {
        // Priority -1 is smaller than 0, so it should be inserted at index 0.
        // The loop `while ($index < $this->list->size())` checks `if ($priority < $current->priority)`
        // -1 < 0 is true immediately. Break. Insert at 0.
        $this->queue->enqueue("best", -1);
    }

    /**
     * Worst Case: Enqueue item with priority higher than all existing (inserted at end).
     * 
     * @Revs(1000)
     * @Iterations(5)
     * @BeforeMethods({"setUp"})
     */
    public function benchWorstCase()
    {
        // Priority 2000 is larger than all existing (max 999).
        // Loop runs through entire list.
        $this->queue->enqueue("worst", 2000);
    }
}
