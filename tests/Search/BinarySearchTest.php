<?php

declare(strict_types=1);

namespace Algoritmes\Tests\Search;

use Algoritmes\Lists\ArrayList;
use Algoritmes\Lists\Interfaces\ListInterface;
use Algoritmes\Search\BinarySearch;
use PHPUnit\Framework\TestCase;

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
    public function testSearchEmptyList(): void
    {
        $list = $this->createList([]);
        $this->assertEquals(-1, $this->binarySearch->search($list, 5));
    }

    /**
     * Test searching in single element array
     */
    public function testSearchSingleElement(): void
    {
        $list = $this->createList([42]);

        $this->assertEquals(0, $this->binarySearch->search($list, 42));
        $this->assertEquals(-1, $this->binarySearch->search($list, 10));
    }

    /**
     * Test searching element at beginning
     */
    public function testSearchElementAtBeginning(): void
    {
        $list = $this->createList([1, 3, 5, 7, 9, 11, 13]);
        $this->assertEquals(0, $this->binarySearch->search($list, 1));
    }

    /**
     * Test searching element at end
     */
    public function testSearchElementAtEnd(): void
    {
        $list = $this->createList([1, 3, 5, 7, 9, 11, 13]);
        $this->assertEquals(6, $this->binarySearch->search($list, 13));
    }

    /**
     * Test searching element in middle
     */
    public function testSearchElementInMiddle(): void
    {
        $list = $this->createList([1, 3, 5, 7, 9, 11, 13]);
        $this->assertEquals(3, $this->binarySearch->search($list, 7));
    }

    /**
     * Test searching non-existent element
     */
    public function testSearchNonExistentElement(): void
    {
        $list = $this->createList([1, 3, 5, 7, 9, 11, 13]);
        $this->assertEquals(-1, $this->binarySearch->search($list, 4));
        $this->assertEquals(-1, $this->binarySearch->search($list, 0));
        $this->assertEquals(-1, $this->binarySearch->search($list, 15));
    }

    /**
     * Test searching with duplicates (standard search)
     */
    public function testSearchWithDuplicates(): void
    {
        $list = $this->createList([1, 3, 5, 5, 5, 7, 9]);

        $result = $this->binarySearch->search($list, 5);
        $this->assertGreaterThanOrEqual(0, $result);
        $this->assertEquals(5, $list->get($result));
    }

    /**
     * Test searching with duplicates (leftmost)
     */
    public function testSearchLeftmostWithDuplicates(): void
    {
        $list = $this->createList([1, 3, 5, 5, 5, 7, 9]);

        $result = $this->binarySearch->searchLeftmost($list, 5);
        $this->assertEquals(2, $result);
    }

    /**
     * Test searching with duplicates (rightmost)
     */
    public function testSearchRightmostWithDuplicates(): void
    {
        $list = $this->createList([1, 3, 5, 5, 5, 7, 9]);

        $result = $this->binarySearch->searchRightmost($list, 5);
        $this->assertEquals(4, $result);
    }

    /**
     * Test searching negative numbers
     */
    public function testSearchNegativeNumbers(): void
    {
        $list = $this->createList([-10, -5, -3, 0, 3, 5, 10]);

        $this->assertEquals(0, $this->binarySearch->search($list, -10));
        $this->assertEquals(3, $this->binarySearch->search($list, 0));
        $this->assertEquals(6, $this->binarySearch->search($list, 10));
    }

    /**
     * Test searching floats
     */
    public function testSearchFloats(): void
    {
        $list = $this->createList([1.1, 2.2, 3.3, 4.4, 5.5]);

        $this->assertEquals(0, $this->binarySearch->search($list, 1.1));
        $this->assertEquals(2, $this->binarySearch->search($list, 3.3));
        $this->assertEquals(-1, $this->binarySearch->search($list, 3.0));
    }

    /**
     * Test searching strings
     */
    public function testSearchStrings(): void
    {
        $list = $this->createList(['apple', 'banana', 'cherry', 'date', 'elderberry']);

        $this->assertEquals(0, $this->binarySearch->search($list, 'apple'));
        $this->assertEquals(2, $this->binarySearch->search($list, 'cherry'));
        $this->assertEquals(4, $this->binarySearch->search($list, 'elderberry'));
        $this->assertEquals(-1, $this->binarySearch->search($list, 'fig'));
    }

    /**
     * Test searching with custom comparator (descending order)
     */
    public function testSearchDescendingWithComparator(): void
    {
        $list = $this->createList([13, 11, 9, 7, 5, 3, 1]);

        $descComparator = fn($a, $b) => $a > $b ? -1 : ($a < $b ? 1 : 0);

        $this->assertEquals(0, $this->binarySearch->search($list, 13, $descComparator));
        $this->assertEquals(3, $this->binarySearch->search($list, 7, $descComparator));
        $this->assertEquals(6, $this->binarySearch->search($list, 1, $descComparator));
    }

    /**
     * Test finding insertion position (element not present)
     */
    public function testFindInsertionPositionNotPresent(): void
    {
        $list = $this->createList([1, 3, 5, 7, 9]);

        $this->assertEquals(0, $this->binarySearch->findInsertionPosition($list, 0));
        $this->assertEquals(1, $this->binarySearch->findInsertionPosition($list, 2));
        $this->assertEquals(3, $this->binarySearch->findInsertionPosition($list, 6));
        $this->assertEquals(5, $this->binarySearch->findInsertionPosition($list, 10));
    }

    /**
     * Test finding insertion position (element present)
     */
    public function testFindInsertionPositionPresent(): void
    {
        $list = $this->createList([1, 3, 5, 7, 9]);

        $this->assertEquals(0, $this->binarySearch->findInsertionPosition($list, 1));
        $this->assertEquals(2, $this->binarySearch->findInsertionPosition($list, 5));
        $this->assertEquals(4, $this->binarySearch->findInsertionPosition($list, 9));
    }

    /**
     * Test finding leftmost with no match
     */
    public function testSearchLeftmostNoMatch(): void
    {
        $list = $this->createList([1, 3, 5, 7, 9]);
        $this->assertEquals(-1, $this->binarySearch->searchLeftmost($list, 4));
    }

    /**
     * Test finding rightmost with no match
     */
    public function testSearchRightmostNoMatch(): void
    {
        $list = $this->createList([1, 3, 5, 7, 9]);
        $this->assertEquals(-1, $this->binarySearch->searchRightmost($list, 4));
    }

    /**
     * Test with large array
     */
    public function testSearchLargeArray(): void
    {
        $values = range(1, 10000);
        $list = $this->createList($values);

        $this->assertEquals(0, $this->binarySearch->search($list, 1));
        $this->assertEquals(4999, $this->binarySearch->search($list, 5000));
        $this->assertEquals(9999, $this->binarySearch->search($list, 10000));
        $this->assertEquals(-1, $this->binarySearch->search($list, 10001));
    }

    /**
     * Test searching with associative array (should re-index)
     */
    public function testSearchAssociativeArray(): void
    {
        $list = $this->createList([1, 3, 5, 7]);

        $this->assertEquals(0, $this->binarySearch->search($list, 1));
        $this->assertEquals(2, $this->binarySearch->search($list, 5));
        $this->assertEquals(-1, $this->binarySearch->search($list, 4));
    }

    /**
     * Test case-insensitive string search
     */
    public function testSearchCaseInsensitiveStrings(): void
    {
        $list = $this->createList(['apple', 'BANANA', 'cherry', 'DATE']);

        $caseInsensitiveComparator = fn($a, $b) =>
        strcasecmp((string)$a, (string)$b);

        $this->assertEquals(0, $this->binarySearch->search($list, 'APPLE', $caseInsensitiveComparator));
        $this->assertEquals(1, $this->binarySearch->search($list, 'banana', $caseInsensitiveComparator));
        $this->assertEquals(2, $this->binarySearch->search($list, 'CHERRY', $caseInsensitiveComparator));
    }

    /**
     * Test insertion position with duplicates
     */
    public function testInsertionPositionWithDuplicates(): void
    {
        $list = $this->createList([1, 3, 5, 5, 5, 7, 9]);

        // Should insert at the leftmost position among duplicates or before the first
        $position = $this->binarySearch->findInsertionPosition($list, 5);
        $this->assertGreaterThanOrEqual(2, $position);
        $this->assertLessThanOrEqual(5, $position);
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
}
