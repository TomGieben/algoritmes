<?php

use Algoritmes\Algoritmes\MergeSort;
use Algoritmes\Algoritmes\LinkedList;
use Algoritmes\Enums\SortDirection;

class MergeSortBench
{
    private $sortedList;
    private $randomList;
    private $size = 100;

    public function setUp()
    {
        // Sorted List
        $this->sortedList = new LinkedList();
        for ($i = 0; $i < $this->size; $i++) {
            $this->sortedList->add($i);
        }

        // Random List
        $this->randomList = new LinkedList();
        $data = range(0, $this->size - 1);
        shuffle($data);
        foreach ($data as $val) {
            $this->randomList->add($val);
        }
    }

    /**
     * Best Case: Already sorted (though MergeSort is O(n log n) anyway).
     * 
     * @Revs(10)
     * @Iterations(3)
     * @BeforeMethods({"setUp"})
     */
    public function benchBestCase()
    {
        $sorter = new MergeSort($this->sortedList);
        $sorter->sort(SortDirection::ASCENDING);
    }

    /**
     * Worst Case: Random order (same complexity, but practically maybe slower due to more swaps/merges).
     * 
     * @Revs(10)
     * @Iterations(3)
     * @BeforeMethods({"setUp"})
     */
    public function benchWorstCase()
    {
        $sorter = new MergeSort($this->randomList);
        $sorter->sort(SortDirection::ASCENDING);
    }
}
