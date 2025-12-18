<?php

declare(strict_types=1);

namespace Algoritmes\Tests\Sort;

use Algoritmes\Lists\ArrayList;
use Algoritmes\Lists\Interfaces\ListInterface;
use Algoritmes\Sort\MergeSort;
use Algoritmes\Sort\QuickSort;
use PHPUnit\Framework\TestCase;

class SortTest extends TestCase
{
    private MergeSort $mergeSort;
    private QuickSort $quickSort;

    protected function setUp(): void
    {
        $this->mergeSort = new MergeSort();
        $this->quickSort = new QuickSort();
    }

    /**
     * Test sorting empty array
     */
    public function testSortEmptyList(): void
    {
        $listForMerge = $this->createList([]);
        $listForQuick = $this->createList([]);

        $this->assertEquals([], $this->listToArray($this->mergeSort->sort($listForMerge)));
        $this->assertEquals([], $this->listToArray($this->quickSort->sort($listForQuick)));
    }

    /**
     * Test sorting single element array
     */
    public function testSortSingleElement(): void
    {
        $values = [42];
        $this->assertEquals([42], $this->listToArray($this->mergeSort->sort($this->createList($values))));
        $this->assertEquals([42], $this->listToArray($this->quickSort->sort($this->createList($values))));
    }

    /**
     * Test sorting already sorted array
     */
    public function testSortAlreadySorted(): void
    {
        $values = [1, 2, 3, 4, 5];
        $this->assertEquals($values, $this->listToArray($this->mergeSort->sort($this->createList($values))));
        $this->assertEquals($values, $this->listToArray($this->quickSort->sort($this->createList($values))));
    }

    /**
     * Test sorting reverse sorted array
     */
    public function testSortReverseSorted(): void
    {
        $values = [5, 4, 3, 2, 1];
        $expected = [1, 2, 3, 4, 5];
        $this->assertEquals($expected, $this->listToArray($this->mergeSort->sort($this->createList($values))));
        $this->assertEquals($expected, $this->listToArray($this->quickSort->sort($this->createList($values))));
    }

    /**
     * Test sorting unsorted array
     */
    public function testSortUnsortedArray(): void
    {
        $values = [64, 34, 25, 12, 22, 11, 90];
        $expected = [11, 12, 22, 25, 34, 64, 90];
        $this->assertEquals($expected, $this->listToArray($this->mergeSort->sort($this->createList($values))));
        $this->assertEquals($expected, $this->listToArray($this->quickSort->sort($this->createList($values))));
    }

    /**
     * Test sorting array with duplicates
     */
    public function testSortWithDuplicates(): void
    {
        $values = [5, 2, 8, 2, 9, 1, 5, 5];
        $expected = [1, 2, 2, 5, 5, 5, 8, 9];
        $this->assertEquals($expected, $this->listToArray($this->mergeSort->sort($this->createList($values))));
        $this->assertEquals($expected, $this->listToArray($this->quickSort->sort($this->createList($values))));
    }

    /**
     * Test sorting array with negative numbers
     */
    public function testSortWithNegativeNumbers(): void
    {
        $values = [-5, 3, -2, 8, -1, 0, 4];
        $expected = [-5, -2, -1, 0, 3, 4, 8];
        $this->assertEquals($expected, $this->listToArray($this->mergeSort->sort($this->createList($values))));
        $this->assertEquals($expected, $this->listToArray($this->quickSort->sort($this->createList($values))));
    }

    /**
     * Test sorting array with floats
     */
    public function testSortWithFloats(): void
    {
        $values = [3.5, 1.2, 4.8, 2.1, 3.5];
        $expected = [1.2, 2.1, 3.5, 3.5, 4.8];
        $this->assertEquals($expected, $this->listToArray($this->mergeSort->sort($this->createList($values))));
        $this->assertEquals($expected, $this->listToArray($this->quickSort->sort($this->createList($values))));
    }

    /**
     * Test sorting strings
     */
    public function testSortStrings(): void
    {
        $values = ['dog', 'cat', 'elephant', 'ant', 'bear'];
        $expected = ['ant', 'bear', 'cat', 'dog', 'elephant'];
        $this->assertEquals($expected, $this->listToArray($this->mergeSort->sort($this->createList($values))));
        $this->assertEquals($expected, $this->listToArray($this->quickSort->sort($this->createList($values))));
    }

    /**
     * Test sorting with custom comparator (descending order)
     */
    public function testSortDescendingWithComparator(): void
    {
        $values = [3, 1, 4, 1, 5, 9, 2, 6];
        $expected = [9, 6, 5, 4, 3, 2, 1, 1];

        $descComparator = fn($a, $b) => $a > $b ? -1 : ($a < $b ? 1 : 0);

        $this->assertEquals($expected, $this->listToArray($this->mergeSort->sort($this->createList($values), $descComparator)));
        $this->assertEquals($expected, $this->listToArray($this->quickSort->sort($this->createList($values), $descComparator)));
    }

    /**
     * Test sorting with custom comparator (case-insensitive strings)
     * Note: The order of items with same case-insensitive value may vary
     */
    public function testSortCaseInsensitiveStrings(): void
    {
        $values = ['Banana', 'apple', 'Cherry', 'banana'];

        $caseInsensitiveComparator = fn($a, $b) =>
        strcasecmp((string)$a, (string)$b);

        $result1 = $this->mergeSort->sort($this->createList($values), $caseInsensitiveComparator);
        $result2 = $this->quickSort->sort($this->createList($values), $caseInsensitiveComparator);

        $resultArray1 = $this->listToArray($result1);
        $resultArray2 = $this->listToArray($result2);

        $this->assertCount(4, $resultArray1);
        $this->assertCount(4, $resultArray2);
        $this->assertEquals('apple', $resultArray1[0]);
        $this->assertEquals('apple', $resultArray2[0]);
    }

    /**
     * Test sorting large array
     */
    public function testSortLargeArray(): void
    {
        // Generate random array
        $values = [];
        for ($i = 0; $i < 1000; $i++) {
            $values[] = rand(1, 10000);
        }

        $expected = $values;
        sort($expected);

        $this->assertEquals($expected, $this->listToArray($this->mergeSort->sort($this->createList($values))));
        $this->assertEquals($expected, $this->listToArray($this->quickSort->sort($this->createList($values))));
    }

    /**
     * Test that sorting doesn't modify original array
     */
    public function testSortingDoesNotModifyOriginal(): void
    {
        $originalList = $this->createList([5, 2, 8, 1]);
        $cloneForMerge = $this->cloneList($originalList);
        $cloneForQuick = $this->cloneList($originalList);

        $this->mergeSort->sort($cloneForMerge);
        $this->assertEquals([1, 2, 5, 8], $this->listToArray($cloneForMerge));
        $this->assertEquals([5, 2, 8, 1], $this->listToArray($originalList));

        $this->quickSort->sort($cloneForQuick);
        $this->assertEquals([1, 2, 5, 8], $this->listToArray($cloneForQuick));
        $this->assertEquals([5, 2, 8, 1], $this->listToArray($originalList));
    }

    /**
     * Test sorting array with numeric keys
     */
    public function testSortWithNumericKeys(): void
    {
        $values = array_values([10 => 5, 20 => 2, 30 => 8, 5 => 1]);
        $expected = [1, 2, 5, 8];

        $result1 = $this->mergeSort->sort($this->createList($values));
        $result2 = $this->quickSort->sort($this->createList($values));

        $this->assertEquals($expected, $this->listToArray($result1));
        $this->assertEquals($expected, $this->listToArray($result2));
    }

    private function createList(array $items): ListInterface
    {
        if ($items === []) {
            return new ArrayList('NULL');
        }

        $type = gettype(reset($items));
        $list = new ArrayList($type);

        foreach ($items as $item) {
            $list->add($item);
        }

        return $list;
    }

    private function listToArray(ListInterface $list): array
    {
        return iterator_to_array($list);
    }

    private function cloneList(ListInterface $list): ListInterface
    {
        return $this->createList($this->listToArray($list));
    }
}
