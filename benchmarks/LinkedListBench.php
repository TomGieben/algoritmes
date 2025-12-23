<?php

use Algoritmes\Algoritmes\LinkedList;

class LinkedListBench
{
    private $list;
    private $size = 1000;

    public function setUp()
    {
        $this->list = new LinkedList();
        for ($i = 0; $i < $this->size; $i++) {
            $this->list->add($i);
        }
    }

    /**
     * Best Case: Add to end (tail pointer optimization) or insert at 0.
     * We'll test insert at 0 as it's O(1).
     * 
     * @Revs(1000)
     * @Iterations(5)
     */
    public function benchBestCase()
    {
        $list = new LinkedList();
        $list->insert(0, 1);
    }

    /**
     * Worst Case: Get element at middle/end (requires traversal).
     * 
     * @Revs(1000)
     * @Iterations(5)
     * @BeforeMethods({"setUp"})
     */
    public function benchWorstCase()
    {
        // Accessing the last element requires traversing the whole list
        $this->list->get($this->size - 1);
    }
}
