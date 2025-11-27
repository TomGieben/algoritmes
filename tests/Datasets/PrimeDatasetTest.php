<?php

declare(strict_types=1);

namespace Algoritmes\Tests\Datasets;

use PHPUnit\Framework\TestCase;
use Algoritmes\Datasets\PrimeDataset;

class PrimeDatasetTest extends TestCase
{
    private PrimeDataset $dataset;

    protected function setUp(): void
    {
        $this->dataset = new PrimeDataset();
    }

    /**
     * Test dataset loads correctly
     */
    public function testDatasetLoads(): void
    {
        $this->assertFalse($this->dataset->isLoaded());
        $this->dataset->load();
        $this->assertTrue($this->dataset->isLoaded());
    }

    /**
     * Test count returns correct number
     */
    public function testCount(): void
    {
        $this->assertEquals(1000000, count($this->dataset));
    }

    /**
     * Test getting columns
     */
    public function testGetColumns(): void
    {
        $columns = $this->dataset->getColumns();
        $this->assertContains('Rank', $columns);
        $this->assertContains('Num', $columns);
        $this->assertContains('Interval', $columns);
    }

    /**
     * Test getting first row
     */
    public function testGetFirstRow(): void
    {
        $row = $this->dataset->getRow(0);

        $this->assertNotNull($row);
        $this->assertEquals(1, $row['Rank']);
        $this->assertEquals(2, $row['Num']);
        $this->assertEquals(2, $row['Interval']);
    }

    /**
     * Test getting specific row
     */
    public function testGetSpecificRow(): void
    {
        $row = $this->dataset->getRow(4); // 5th prime (11)

        $this->assertNotNull($row);
        $this->assertEquals(5, $row['Rank']);
        $this->assertEquals(11, $row['Num']);
    }

    /**
     * Test getting non-existent row
     */
    public function testGetNonExistentRow(): void
    {
        $row = $this->dataset->getRow(999999999);
        $this->assertNull($row);
    }

    /**
     * Test getPrimes
     */
    public function testGetPrimes(): void
    {
        $primes = $this->dataset->getFirstN(10);

        $expected = [2, 3, 5, 7, 11, 13, 17, 19, 23, 29];
        $this->assertEquals($expected, $primes);
    }

    /**
     * Test getNthPrime
     */
    public function testGetNthPrime(): void
    {
        $this->assertEquals(2, $this->dataset->getNthPrime(1));
        $this->assertEquals(3, $this->dataset->getNthPrime(2));
        $this->assertEquals(5, $this->dataset->getNthPrime(3));
        $this->assertEquals(7, $this->dataset->getNthPrime(4));
        $this->assertEquals(11, $this->dataset->getNthPrime(5));
    }

    /**
     * Test isPrime with known primes
     */
    public function testIsPrimeWithPrimes(): void
    {
        $this->assertTrue($this->dataset->isPrime(2));
        $this->assertTrue($this->dataset->isPrime(3));
        $this->assertTrue($this->dataset->isPrime(5));
        $this->assertTrue($this->dataset->isPrime(7));
        $this->assertTrue($this->dataset->isPrime(11));
        $this->assertTrue($this->dataset->isPrime(97));
    }

    /**
     * Test isPrime with non-primes
     */
    public function testIsPrimeWithNonPrimes(): void
    {
        $this->assertFalse($this->dataset->isPrime(1));
        $this->assertFalse($this->dataset->isPrime(4));
        $this->assertFalse($this->dataset->isPrime(6));
        $this->assertFalse($this->dataset->isPrime(8));
        $this->assertFalse($this->dataset->isPrime(9));
        $this->assertFalse($this->dataset->isPrime(100));
    }

    /**
     * Test getPrimesInRange
     */
    public function testGetPrimesInRange(): void
    {
        $primes = $this->dataset->getPrimesInRange(10, 30);

        $expected = [11, 13, 17, 19, 23, 29];
        $this->assertEquals($expected, $primes);
    }

    /**
     * Test getFirstN
     */
    public function testGetFirstN(): void
    {
        $first5 = $this->dataset->getFirstN(5);

        $this->assertCount(5, $first5);
        $this->assertEquals([2, 3, 5, 7, 11], $first5);
    }

    /**
     * Test slice
     */
    public function testSlice(): void
    {
        $slice = $this->dataset->slice(0, 3);

        $this->assertCount(3, $slice);
        $this->assertEquals(2, $slice[0]['Num']);
        $this->assertEquals(3, $slice[1]['Num']);
        $this->assertEquals(5, $slice[2]['Num']);
    }

    /**
     * Test getIntervals
     */
    public function testGetIntervals(): void
    {
        $slice = $this->dataset->slice(0, 5);

        // Intervals: 2->3=1, 3->5=2, 5->7=2, 7->11=4
        $this->assertEquals(2, $slice[0]['Interval']);
        $this->assertEquals(1, $slice[1]['Interval']);
        $this->assertEquals(2, $slice[2]['Interval']);
    }

    /**
     * Test getStatistics
     */
    public function testGetStatistics(): void
    {
        $stats = $this->dataset->getStatistics();

        $this->assertArrayHasKey('count', $stats);
        $this->assertArrayHasKey('smallest', $stats);
        $this->assertArrayHasKey('largest', $stats);
        $this->assertArrayHasKey('avg_interval', $stats);
        $this->assertArrayHasKey('max_interval', $stats);
        $this->assertArrayHasKey('min_interval', $stats);

        $this->assertEquals(1000000, $stats['count']);
        $this->assertEquals(2, $stats['smallest']);
    }

    /**
     * Test iterator
     */
    public function testIterator(): void
    {
        $count = 0;
        foreach ($this->dataset as $row) {
            $count++;
            if ($count >= 5) break;
        }

        $this->assertEquals(5, $count);
    }

    /**
     * Test countable interface
     */
    public function testCountable(): void
    {
        $this->assertEquals(1000000, count($this->dataset));
    }

    /**
     * Test data types are correct
     */
    public function testDataTypes(): void
    {
        $row = $this->dataset->getRow(0);

        $this->assertIsInt($row['Rank']);
        $this->assertIsInt($row['Num']);
        $this->assertIsInt($row['Interval']);
    }

    /**
     * Test lazy loading
     */
    public function testLazyLoading(): void
    {
        $dataset = new PrimeDataset();
        $this->assertFalse($dataset->isLoaded());

        // Accessing data should trigger load
        $row = $dataset->getRow(0);
        $this->assertTrue($dataset->isLoaded());
        $this->assertNotNull($row);
    }

    /**
     * Test toArray
     */
    public function testToArray(): void
    {
        $array = $this->dataset->toArray();

        $this->assertIsArray($array);
        $this->assertCount(1000000, $array);
    }

    /**
     * Test getColumn
     */
    public function testGetColumn(): void
    {
        $nums = $this->dataset->getColumn('Num');

        $this->assertIsArray($nums);
        $this->assertEquals(2, $nums[0]);
        $this->assertEquals(3, $nums[1]);
    }

    /**
     * Test invalid column throws exception
     */
    public function testInvalidColumnThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->dataset->getColumn('InvalidColumn');
    }
}
