<?php

use Algoritmes\Algoritmes\DoublyLinkedList;

class DoublyLinkedListBench
{
    private $list;
    private $size = 1000;

    public function setUp()
    {
        $this->list = new DoublyLinkedList();
        for ($i = 0; $i < $this->size; $i++) {
            $this->list->add($i);
        }
    }

    /**
     * Best Case: Add to end or remove from start/end.
     * 
     * @Revs(1000)
     * @Iterations(5)
     */
    public function benchBestCase()
    {
        $list = new DoublyLinkedList();
        $list->add(1); // O(1) with tail pointer
    }

    /**
     * Worst Case: Get element at middle (requires traversal from closest end).
     * 
     * @Revs(1000)
     * @Iterations(5)
     * @BeforeMethods({"setUp"})
     */
    public function benchWorstCase()
    {
        // Accessing the middle element requires traversing half the list
        $this->list->get((int)($this->size / 2));
    }
}
