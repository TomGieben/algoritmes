<?php

declare(strict_types=1);

namespace Algoritmes\Tests\Lists;

use Algoritmes\Lists\ArrayList;
use Algoritmes\Datasets\ListDataset;
use PHPUnit\Framework\TestCase;

class ArrayListTest extends TestCase
{
    private ArrayList $list;

    protected function setUp(): void
    {
        $this->list = new ArrayList('integer');
    }

    /**
     * Test adding items to the list
     */
    public function testAdd(): void
    {
        $this->assertTrue($this->list->isEmpty());
        $this->assertEquals(0, $this->list->count());

        $this->list->add(10);
        $this->assertFalse($this->list->isEmpty());
        $this->assertEquals(1, $this->list->count());
        $this->assertEquals(10, $this->list->get(0));
    }

    /**
     * Test adding multiple items
     */
    public function testAddMultiple(): void
    {
        $dataset = ListDataset::getSmallIntegerDataset();
        foreach ($dataset as $item) {
            $this->list->add($item);
        }

        $this->assertEquals(3, $this->list->count());
        $this->assertEquals(1, $this->list->get(0));
        $this->assertEquals(2, $this->list->get(1));
        $this->assertEquals(3, $this->list->get(2));
    }

    /**
     * Test adding with full dataset
     */
    public function testAddWithLargeDataset(): void
    {
        $dataset = ListDataset::getIntegerDataset();
        foreach ($dataset as $item) {
            $this->list->add($item);
        }

        $this->assertEquals(count($dataset), $this->list->count());
        foreach ($dataset as $index => $item) {
            $this->assertEquals($item, $this->list->get($index));
        }
    }

    /**
     * Test insert at beginning
     */
    public function testInsertAtBeginning(): void
    {
        $this->list->add(20);
        $this->list->add(30);

        $this->list->insert(0, 10);

        $this->assertEquals(3, $this->list->count());
        $this->assertEquals(10, $this->list->get(0));
        $this->assertEquals(20, $this->list->get(1));
        $this->assertEquals(30, $this->list->get(2));
    }

    /**
     * Test insert in middle
     */
    public function testInsertInMiddle(): void
    {
        $this->list->add(10);
        $this->list->add(30);

        $this->list->insert(1, 20);

        $this->assertEquals(3, $this->list->count());
        $this->assertEquals(10, $this->list->get(0));
        $this->assertEquals(20, $this->list->get(1));
        $this->assertEquals(30, $this->list->get(2));
    }

    /**
     * Test insert at end
     */
    public function testInsertAtEnd(): void
    {
        $this->list->add(10);
        $this->list->add(20);

        $this->list->insert(2, 30);

        $this->assertEquals(3, $this->list->count());
        $this->assertEquals(30, $this->list->get(2));
    }

    /**
     * Test get method
     */
    public function testGet(): void
    {
        $dataset = ListDataset::getSmallIntegerDataset();
        foreach ($dataset as $item) {
            $this->list->add($item);
        }

        for ($i = 0; $i < count($dataset); $i++) {
            $this->assertEquals($dataset[$i], $this->list->get($i));
        }
    }

    /**
     * Test set method
     */
    public function testSet(): void
    {
        $this->list->add(10);
        $this->list->add(20);
        $this->list->add(30);

        $this->list->set(1, 25);
        $this->assertEquals(25, $this->list->get(1));
        $this->assertEquals(3, $this->list->count());
    }

    /**
     * Test remove at index
     */
    public function testRemoveAt(): void
    {
        $this->list->add(10);
        $this->list->add(20);
        $this->list->add(30);

        $removed = $this->list->removeAt(1);

        $this->assertEquals(20, $removed);
        $this->assertEquals(2, $this->list->count());
        $this->assertEquals(10, $this->list->get(0));
        $this->assertEquals(30, $this->list->get(1));
    }

    /**
     * Test remove first element
     */
    public function testRemoveFirst(): void
    {
        $this->list->add(10);
        $this->list->add(20);
        $this->list->add(30);

        $removed = $this->list->removeAt(0);

        $this->assertEquals(10, $removed);
        $this->assertEquals(2, $this->list->count());
        $this->assertEquals(20, $this->list->get(0));
    }

    /**
     * Test remove last element
     */
    public function testRemoveLast(): void
    {
        $this->list->add(10);
        $this->list->add(20);
        $this->list->add(30);

        $removed = $this->list->removeAt(2);

        $this->assertEquals(30, $removed);
        $this->assertEquals(2, $this->list->count());
        $this->assertEquals(20, $this->list->get(1));
    }

    /**
     * Test clear method
     */
    public function testClear(): void
    {
        $dataset = ListDataset::getIntegerDataset();
        foreach ($dataset as $item) {
            $this->list->add($item);
        }

        $this->assertFalse($this->list->isEmpty());
        $this->list->clear();
        $this->assertTrue($this->list->isEmpty());
        $this->assertEquals(0, $this->list->count());
    }

    /**
     * Test isEmpty method
     */
    public function testIsEmpty(): void
    {
        $this->assertTrue($this->list->isEmpty());
        $this->list->add(1);
        $this->assertFalse($this->list->isEmpty());
        $this->list->removeAt(0);
        $this->assertTrue($this->list->isEmpty());
    }

    /**
     * Test count method
     */
    public function testCount(): void
    {
        $this->assertEquals(0, $this->list->count());

        for ($i = 1; $i <= 5; $i++) {
            $this->list->add($i);
            $this->assertEquals($i, $this->list->count());
        }
    }

    /**
     * Test iterator
     */
    public function testGetIterator(): void
    {
        $dataset = ListDataset::getSmallIntegerDataset();
        foreach ($dataset as $item) {
            $this->list->add($item);
        }

        $result = [];
        foreach ($this->list as $item) {
            $result[] = $item;
        }

        $this->assertEquals($dataset, $result);
    }

    /**
     * Test type validation
     */
    public function testTypeValidation(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->list->add('string'); // ArrayList is for integers
    }

    /**
     * Test string list
     */
    public function testStringList(): void
    {
        $stringList = new ArrayList('string');
        $dataset = ListDataset::getSmallStringDataset();

        foreach ($dataset as $item) {
            $stringList->add($item);
        }

        $this->assertEquals(count($dataset), $stringList->count());
        foreach ($dataset as $index => $item) {
            $this->assertEquals($item, $stringList->get($index));
        }
    }

    /**
     * Test with duplicate values
     */
    public function testDuplicateValues(): void
    {
        $dataset = ListDataset::getDuplicateDataset();
        foreach ($dataset as $item) {
            $this->list->add($item);
        }

        $this->assertEquals(8, $this->list->count());
        $this->assertEquals(5, $this->list->get(0));
        $this->assertEquals(5, $this->list->get(1));
    }

    /**
     * Test large dataset performance
     */
    public function testLargeDataset(): void
    {
        $dataset = ListDataset::getLargeIntegerDataset(100);

        foreach ($dataset as $item) {
            $this->list->add($item);
        }

        $this->assertEquals(100, $this->list->count());

        // Verify some random elements
        $this->assertEquals($dataset[0], $this->list->get(0));
        $this->assertEquals($dataset[50], $this->list->get(50));
        $this->assertEquals($dataset[99], $this->list->get(99));
    }

    /**
     * Test out of bounds exception
     */
    public function testGetOutOfBounds(): void
    {
        $this->list->add(10);

        $this->expectException(\OutOfBoundsException::class);
        $this->list->get(5);
    }
}
