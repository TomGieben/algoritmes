<?php

use Algoritmes\Algoritmes\QuickSort;
use Algoritmes\Algoritmes\LinkedList;
use Algoritmes\Enums\SortDirection;
use Algoritmes\Helpers\IntComparator;

class QuickSortBench
{
    private $sortedList;
    private $randomList;

    public function setUp()
    {
        $size = 100; // Keep small due to O(n^2) overhead in LinkedList access

        // Worst Case: Already sorted (pivot is always extreme)
        $this->sortedList = new LinkedList();
        for ($i = 0; $i < $size; $i++) {
            $this->sortedList->add($i);
        }

        // Best/Average Case: Random order
        $this->randomList = new LinkedList();
        $data = range(0, $size - 1);
        shuffle($data);
        foreach ($data as $val) {
            $this->randomList->add($val);
        }
    }

    /**
     * @Revs(10)
     * @Iterations(3)
     * @BeforeMethods({"setUp"})
     */
    public function benchBestCase()
    {
        $sorter = new QuickSort($this->randomList, new IntComparator());
        $sorter->sort(SortDirection::ASCENDING);
    }

    /**
     * @Revs(10)
     * @Iterations(3)
     * @BeforeMethods({"setUp"})
     */
    public function benchWorstCase()
    {
        $sorter = new QuickSort($this->sortedList, new IntComparator());
        $sorter->sort(SortDirection::ASCENDING);
    }
}
