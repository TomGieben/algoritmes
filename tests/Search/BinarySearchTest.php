<?php

declare(strict_types=1);

namespace Algoritmes\Tests\Search;

use PHPUnit\Framework\TestCase;
use Algoritmes\Search\BinarySearch;

class BinarySearchTest extends TestCase
{
    private BinarySearch $binarySearch;

    protected function setUp(): void
    {
        $this->binarySearch = new BinarySearch();
    }

    /**
     * Test searching in empty array
     */
    public function testSearchEmptyArray(): void
    {
        $array = [];
        $this->assertEquals(-1, $this->binarySearch->search($array, 5));
    }

    /**
     * Test searching in single element array
     */
    public function testSearchSingleElement(): void
    {
        $array = [42];

        $this->assertEquals(0, $this->binarySearch->search($array, 42));
        $this->assertEquals(-1, $this->binarySearch->search($array, 10));
    }

    /**
     * Test searching element at beginning
     */
    public function testSearchElementAtBeginning(): void
    {
        $array = [1, 3, 5, 7, 9, 11, 13];
        $this->assertEquals(0, $this->binarySearch->search($array, 1));
    }

    /**
     * Test searching element at end
     */
    public function testSearchElementAtEnd(): void
    {
        $array = [1, 3, 5, 7, 9, 11, 13];
        $this->assertEquals(6, $this->binarySearch->search($array, 13));
    }

    /**
     * Test searching element in middle
     */
    public function testSearchElementInMiddle(): void
    {
        $array = [1, 3, 5, 7, 9, 11, 13];
        $this->assertEquals(3, $this->binarySearch->search($array, 7));
    }

    /**
     * Test searching non-existent element
     */
    public function testSearchNonExistentElement(): void
    {
        $array = [1, 3, 5, 7, 9, 11, 13];
        $this->assertEquals(-1, $this->binarySearch->search($array, 4));
        $this->assertEquals(-1, $this->binarySearch->search($array, 0));
        $this->assertEquals(-1, $this->binarySearch->search($array, 15));
    }

    /**
     * Test searching with duplicates (standard search)
     */
    public function testSearchWithDuplicates(): void
    {
        $array = [1, 3, 5, 5, 5, 7, 9];

        // Standard search should find one of the 5's
        $result = $this->binarySearch->search($array, 5);
        $this->assertGreaterThanOrEqual(0, $result);
        $this->assertEquals(5, $array[$result]);
    }

    /**
     * Test searching with duplicates (leftmost)
     */
    public function testSearchLeftmostWithDuplicates(): void
    {
        $array = [1, 3, 5, 5, 5, 7, 9];

        $result = $this->binarySearch->searchLeftmost($array, 5);
        $this->assertEquals(2, $result);
    }

    /**
     * Test searching with duplicates (rightmost)
     */
    public function testSearchRightmostWithDuplicates(): void
    {
        $array = [1, 3, 5, 5, 5, 7, 9];

        $result = $this->binarySearch->searchRightmost($array, 5);
        $this->assertEquals(4, $result);
    }

    /**
     * Test searching negative numbers
     */
    public function testSearchNegativeNumbers(): void
    {
        $array = [-10, -5, -3, 0, 3, 5, 10];

        $this->assertEquals(0, $this->binarySearch->search($array, -10));
        $this->assertEquals(3, $this->binarySearch->search($array, 0));
        $this->assertEquals(6, $this->binarySearch->search($array, 10));
    }

    /**
     * Test searching floats
     */
    public function testSearchFloats(): void
    {
        $array = [1.1, 2.2, 3.3, 4.4, 5.5];

        $this->assertEquals(0, $this->binarySearch->search($array, 1.1));
        $this->assertEquals(2, $this->binarySearch->search($array, 3.3));
        $this->assertEquals(-1, $this->binarySearch->search($array, 3.0));
    }

    /**
     * Test searching strings
     */
    public function testSearchStrings(): void
    {
        $array = ['apple', 'banana', 'cherry', 'date', 'elderberry'];

        $this->assertEquals(0, $this->binarySearch->search($array, 'apple'));
        $this->assertEquals(2, $this->binarySearch->search($array, 'cherry'));
        $this->assertEquals(4, $this->binarySearch->search($array, 'elderberry'));
        $this->assertEquals(-1, $this->binarySearch->search($array, 'fig'));
    }

    /**
     * Test searching with custom comparator (descending order)
     */
    public function testSearchDescendingWithComparator(): void
    {
        $array = [13, 11, 9, 7, 5, 3, 1];

        $descComparator = fn($a, $b) => $a > $b ? -1 : ($a < $b ? 1 : 0);

        $this->assertEquals(0, $this->binarySearch->search($array, 13, $descComparator));
        $this->assertEquals(3, $this->binarySearch->search($array, 7, $descComparator));
        $this->assertEquals(6, $this->binarySearch->search($array, 1, $descComparator));
    }

    /**
     * Test finding insertion position (element not present)
     */
    public function testFindInsertionPositionNotPresent(): void
    {
        $array = [1, 3, 5, 7, 9];

        $this->assertEquals(0, $this->binarySearch->findInsertionPosition($array, 0));
        $this->assertEquals(1, $this->binarySearch->findInsertionPosition($array, 2));
        $this->assertEquals(3, $this->binarySearch->findInsertionPosition($array, 6));
        $this->assertEquals(5, $this->binarySearch->findInsertionPosition($array, 10));
    }

    /**
     * Test finding insertion position (element present)
     */
    public function testFindInsertionPositionPresent(): void
    {
        $array = [1, 3, 5, 7, 9];

        $this->assertEquals(0, $this->binarySearch->findInsertionPosition($array, 1));
        $this->assertEquals(2, $this->binarySearch->findInsertionPosition($array, 5));
        $this->assertEquals(4, $this->binarySearch->findInsertionPosition($array, 9));
    }

    /**
     * Test finding leftmost with no match
     */
    public function testSearchLeftmostNoMatch(): void
    {
        $array = [1, 3, 5, 7, 9];
        $this->assertEquals(-1, $this->binarySearch->searchLeftmost($array, 4));
    }

    /**
     * Test finding rightmost with no match
     */
    public function testSearchRightmostNoMatch(): void
    {
        $array = [1, 3, 5, 7, 9];
        $this->assertEquals(-1, $this->binarySearch->searchRightmost($array, 4));
    }

    /**
     * Test with large array
     */
    public function testSearchLargeArray(): void
    {
        $array = [];
        for ($i = 1; $i <= 10000; $i++) {
            $array[] = $i;
        }

        $this->assertEquals(0, $this->binarySearch->search($array, 1));
        $this->assertEquals(4999, $this->binarySearch->search($array, 5000));
        $this->assertEquals(9999, $this->binarySearch->search($array, 10000));
        $this->assertEquals(-1, $this->binarySearch->search($array, 10001));
    }

    /**
     * Test searching with associative array (should re-index)
     */
    public function testSearchAssociativeArray(): void
    {
        $array = ['a' => 1, 'b' => 3, 'c' => 5, 'd' => 7];

        $this->assertEquals(0, $this->binarySearch->search($array, 1));
        $this->assertEquals(2, $this->binarySearch->search($array, 5));
        $this->assertEquals(-1, $this->binarySearch->search($array, 4));
    }

    /**
     * Test case-insensitive string search
     */
    public function testSearchCaseInsensitiveStrings(): void
    {
        $array = ['apple', 'BANANA', 'cherry', 'DATE'];

        $caseInsensitiveComparator = fn($a, $b) =>
        strcasecmp((string)$a, (string)$b);

        $this->assertEquals(0, $this->binarySearch->search($array, 'APPLE', $caseInsensitiveComparator));
        $this->assertEquals(1, $this->binarySearch->search($array, 'banana', $caseInsensitiveComparator));
        $this->assertEquals(2, $this->binarySearch->search($array, 'CHERRY', $caseInsensitiveComparator));
    }

    /**
     * Test insertion position with duplicates
     */
    public function testInsertionPositionWithDuplicates(): void
    {
        $array = [1, 3, 5, 5, 5, 7, 9];

        // Should insert at the leftmost position among duplicates or before the first
        $position = $this->binarySearch->findInsertionPosition($array, 5);
        $this->assertGreaterThanOrEqual(2, $position);
        $this->assertLessThanOrEqual(5, $position);
    }
}
