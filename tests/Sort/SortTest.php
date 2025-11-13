<?php

declare(strict_types=1);

namespace Algoritmes\Tests\Sort;

use PHPUnit\Framework\TestCase;
use Algoritmes\Sort\MergeSort;
use Algoritmes\Sort\QuickSort;

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
    public function testSortEmptyArray(): void
    {
        $array = [];
        $this->assertEquals([], $this->mergeSort->sort($array));
        $this->assertEquals([], $this->quickSort->sort($array));
    }

    /**
     * Test sorting single element array
     */
    public function testSortSingleElement(): void
    {
        $array = [42];
        $this->assertEquals([42], $this->mergeSort->sort($array));
        $this->assertEquals([42], $this->quickSort->sort($array));
    }

    /**
     * Test sorting already sorted array
     */
    public function testSortAlreadySorted(): void
    {
        $array = [1, 2, 3, 4, 5];
        $this->assertEquals([1, 2, 3, 4, 5], $this->mergeSort->sort($array));
        $this->assertEquals([1, 2, 3, 4, 5], $this->quickSort->sort($array));
    }

    /**
     * Test sorting reverse sorted array
     */
    public function testSortReverseSorted(): void
    {
        $array = [5, 4, 3, 2, 1];
        $expected = [1, 2, 3, 4, 5];
        $this->assertEquals($expected, $this->mergeSort->sort($array));
        $this->assertEquals($expected, $this->quickSort->sort($array));
    }

    /**
     * Test sorting unsorted array
     */
    public function testSortUnsortedArray(): void
    {
        $array = [64, 34, 25, 12, 22, 11, 90];
        $expected = [11, 12, 22, 25, 34, 64, 90];
        $this->assertEquals($expected, $this->mergeSort->sort($array));
        $this->assertEquals($expected, $this->quickSort->sort($array));
    }

    /**
     * Test sorting array with duplicates
     */
    public function testSortWithDuplicates(): void
    {
        $array = [5, 2, 8, 2, 9, 1, 5, 5];
        $expected = [1, 2, 2, 5, 5, 5, 8, 9];
        $this->assertEquals($expected, $this->mergeSort->sort($array));
        $this->assertEquals($expected, $this->quickSort->sort($array));
    }

    /**
     * Test sorting array with negative numbers
     */
    public function testSortWithNegativeNumbers(): void
    {
        $array = [-5, 3, -2, 8, -1, 0, 4];
        $expected = [-5, -2, -1, 0, 3, 4, 8];
        $this->assertEquals($expected, $this->mergeSort->sort($array));
        $this->assertEquals($expected, $this->quickSort->sort($array));
    }

    /**
     * Test sorting array with floats
     */
    public function testSortWithFloats(): void
    {
        $array = [3.5, 1.2, 4.8, 2.1, 3.5];
        $expected = [1.2, 2.1, 3.5, 3.5, 4.8];
        $this->assertEquals($expected, $this->mergeSort->sort($array));
        $this->assertEquals($expected, $this->quickSort->sort($array));
    }

    /**
     * Test sorting strings
     */
    public function testSortStrings(): void
    {
        $array = ['dog', 'cat', 'elephant', 'ant', 'bear'];
        $expected = ['ant', 'bear', 'cat', 'dog', 'elephant'];
        $this->assertEquals($expected, $this->mergeSort->sort($array));
        $this->assertEquals($expected, $this->quickSort->sort($array));
    }

    /**
     * Test sorting with custom comparator (descending order)
     */
    public function testSortDescendingWithComparator(): void
    {
        $array = [3, 1, 4, 1, 5, 9, 2, 6];
        $expected = [9, 6, 5, 4, 3, 2, 1, 1];

        $descComparator = fn($a, $b) => $a > $b ? -1 : ($a < $b ? 1 : 0);

        $this->assertEquals($expected, $this->mergeSort->sort($array, $descComparator));
        $this->assertEquals($expected, $this->quickSort->sort($array, $descComparator));
    }

    /**
     * Test sorting with custom comparator (case-insensitive strings)
     * Note: The order of items with same case-insensitive value may vary
     */
    public function testSortCaseInsensitiveStrings(): void
    {
        $array = ['Banana', 'apple', 'Cherry', 'banana'];

        $caseInsensitiveComparator = fn($a, $b) =>
        strcasecmp((string)$a, (string)$b);

        $result1 = $this->mergeSort->sort($array, $caseInsensitiveComparator);
        $result2 = $this->quickSort->sort($array, $caseInsensitiveComparator);

        // Check that all elements are present and first is 'apple' (lowercase)
        $this->assertCount(4, $result1);
        $this->assertCount(4, $result2);
        $this->assertEquals('apple', $result1[0]);
        $this->assertEquals('apple', $result2[0]);
    }

    /**
     * Test sorting large array
     */
    public function testSortLargeArray(): void
    {
        // Generate random array
        $array = [];
        for ($i = 0; $i < 1000; $i++) {
            $array[] = rand(1, 10000);
        }

        $expected = $array;
        sort($expected);

        $this->assertEquals($expected, $this->mergeSort->sort($array));
        $this->assertEquals($expected, $this->quickSort->sort($array));
    }

    /**
     * Test that sorting doesn't modify original array
     */
    public function testSortingDoesNotModifyOriginal(): void
    {
        $array = [5, 2, 8, 1];
        $original = $array;

        $this->mergeSort->sort($array);
        $this->assertEquals($original, $array);

        $this->quickSort->sort($array);
        $this->assertEquals($original, $array);
    }

    /**
     * Test sorting array with numeric keys
     */
    public function testSortWithNumericKeys(): void
    {
        $array = [10 => 5, 20 => 2, 30 => 8, 5 => 1];
        $result1 = $this->mergeSort->sort($array);
        $result2 = $this->quickSort->sort($array);

        // Both should return re-indexed arrays with sorted values
        $this->assertEquals([1, 2, 5, 8], $result1);
        $this->assertEquals([1, 2, 5, 8], $result2);
    }
}
