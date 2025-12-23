<?php

use Algoritmes\Algoritmes\BinarySearch;
use Algoritmes\Algoritmes\LinkedList;
use Algoritmes\Interfaces\IsComparator;

class BinarySearchBench
{
    private $binarySearch;
    private $list;
    private $bestCaseTarget;
    private $worstCaseTarget;

    public function setUp()
    {
        $comparator = new class implements IsComparator {
            public function compare(mixed $a, mixed $b): int
            {
                if ($a == $b) return 0;
                return ($a < $b) ? -1 : 1;
            }
        };

        $this->binarySearch = new BinarySearch($comparator);
        $this->list = new LinkedList();

        $size = 1000;
        for ($i = 0; $i < $size; $i++) {
            $this->list->add($i);
        }

        // Best case: Middle element
        // 0..999. Mid is intdiv(0+999, 2) = 499.
        $this->bestCaseTarget = 499;

        // Worst case: Element not in list
        $this->worstCaseTarget = -1;
    }

    /**
     * @Revs(1000)
     * @Iterations(5)
     * @BeforeMethods({"setUp"})
     */
    public function benchBestCase()
    {
        $this->binarySearch->search($this->list, $this->bestCaseTarget);
    }

    /**
     * @Revs(1000)
     * @Iterations(5)
     * @BeforeMethods({"setUp"})
     */
    public function benchWorstCase()
    {
        $this->binarySearch->search($this->list, $this->worstCaseTarget);
    }
}
