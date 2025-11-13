<?php

declare(strict_types=1);

namespace Algoritmes\Tests\HashTable;

use PHPUnit\Framework\TestCase;
use Algoritmes\HashTable\HashTable;

class HashTableTest extends TestCase
{
    private HashTable $hashTable;

    protected function setUp(): void
    {
        $this->hashTable = new HashTable();
    }

    /**
     * Test creating empty hash table
     */
    public function testCreateEmptyHashTable(): void
    {
        $this->assertTrue($this->hashTable->isEmpty());
        $this->assertEquals(0, $this->hashTable->count());
    }

    /**
     * Test putting and getting single entry
     */
    public function testPutAndGet(): void
    {
        $this->hashTable->put('name', 'John');
        $this->assertEquals('John', $this->hashTable->get('name'));
    }

    /**
     * Test getting non-existent key
     */
    public function testGetNonExistentKey(): void
    {
        $this->assertNull($this->hashTable->get('nonexistent'));
        $this->assertEquals('default', $this->hashTable->get('nonexistent', 'default'));
    }

    /**
     * Test putting multiple entries
     */
    public function testPutMultipleEntries(): void
    {
        $this->hashTable->put('key1', 'value1');
        $this->hashTable->put('key2', 'value2');
        $this->hashTable->put('key3', 'value3');

        $this->assertEquals(3, $this->hashTable->count());
        $this->assertEquals('value1', $this->hashTable->get('key1'));
        $this->assertEquals('value2', $this->hashTable->get('key2'));
        $this->assertEquals('value3', $this->hashTable->get('key3'));
    }

    /**
     * Test updating existing key
     */
    public function testUpdateExistingKey(): void
    {
        $this->hashTable->put('name', 'John');
        $this->assertEquals('John', $this->hashTable->get('name'));
        $this->assertEquals(1, $this->hashTable->count());

        $this->hashTable->put('name', 'Jane');
        $this->assertEquals('Jane', $this->hashTable->get('name'));
        $this->assertEquals(1, $this->hashTable->count());
    }

    /**
     * Test containsKey
     */
    public function testContainsKey(): void
    {
        $this->hashTable->put('exists', 'value');

        $this->assertTrue($this->hashTable->containsKey('exists'));
        $this->assertFalse($this->hashTable->containsKey('notexists'));
    }

    /**
     * Test removing entry
     */
    public function testRemove(): void
    {
        $this->hashTable->put('key1', 'value1');
        $this->hashTable->put('key2', 'value2');

        $removed = $this->hashTable->remove('key1');
        $this->assertEquals('value1', $removed);
        $this->assertEquals(1, $this->hashTable->count());
        $this->assertFalse($this->hashTable->containsKey('key1'));
    }

    /**
     * Test removing non-existent key
     */
    public function testRemoveNonExistent(): void
    {
        $this->assertNull($this->hashTable->remove('nonexistent'));
        $this->assertEquals(0, $this->hashTable->count());
    }

    /**
     * Test clear
     */
    public function testClear(): void
    {
        $this->hashTable->put('key1', 'value1');
        $this->hashTable->put('key2', 'value2');
        $this->assertEquals(2, $this->hashTable->count());

        $this->hashTable->clear();
        $this->assertEquals(0, $this->hashTable->count());
        $this->assertTrue($this->hashTable->isEmpty());
        $this->assertNull($this->hashTable->get('key1'));
    }

    /**
     * Test keys method
     */
    public function testKeys(): void
    {
        $this->hashTable->put('key1', 'value1');
        $this->hashTable->put('key2', 'value2');
        $this->hashTable->put('key3', 'value3');

        $keys = $this->hashTable->keys();
        $this->assertCount(3, $keys);
        $this->assertContains('key1', $keys);
        $this->assertContains('key2', $keys);
        $this->assertContains('key3', $keys);
    }

    /**
     * Test values method
     */
    public function testValues(): void
    {
        $this->hashTable->put('key1', 'value1');
        $this->hashTable->put('key2', 'value2');
        $this->hashTable->put('key3', 'value3');

        $values = $this->hashTable->values();
        $this->assertCount(3, $values);
        $this->assertContains('value1', $values);
        $this->assertContains('value2', $values);
        $this->assertContains('value3', $values);
    }

    /**
     * Test storing different data types
     */
    public function testStoreDifferentTypes(): void
    {
        $this->hashTable->put('string', 'value');
        $this->hashTable->put('integer', 42);
        $this->hashTable->put('float', 3.14);
        $this->hashTable->put('boolean', true);
        $this->hashTable->put('array', [1, 2, 3]);
        $this->hashTable->put('null', null);

        $this->assertEquals('value', $this->hashTable->get('string'));
        $this->assertEquals(42, $this->hashTable->get('integer'));
        $this->assertEquals(3.14, $this->hashTable->get('float'));
        $this->assertTrue($this->hashTable->get('boolean'));
        $this->assertEquals([1, 2, 3], $this->hashTable->get('array'));
        $this->assertNull($this->hashTable->get('null'));
    }

    /**
     * Test automatic resizing
     */
    public function testAutomaticResizing(): void
    {
        // Add many entries to trigger resizing
        for ($i = 0; $i < 50; $i++) {
            $this->hashTable->put("key_$i", "value_$i");
        }

        // Verify all entries are still accessible after resizing
        for ($i = 0; $i < 50; $i++) {
            $this->assertEquals("value_$i", $this->hashTable->get("key_$i"));
        }

        $this->assertEquals(50, $this->hashTable->count());
    }

    /**
     * Test collision handling (separate chaining)
     */
    public function testCollisionHandling(): void
    {
        $hashTable = new HashTable(1); // Force collisions with capacity 1

        $this->hashTable->put('key1', 'value1');
        $this->hashTable->put('key2', 'value2');
        $this->hashTable->put('key3', 'value3');

        $this->assertEquals('value1', $this->hashTable->get('key1'));
        $this->assertEquals('value2', $this->hashTable->get('key2'));
        $this->assertEquals('value3', $this->hashTable->get('key3'));
    }

    /**
     * Test iterator
     */
    public function testIterator(): void
    {
        $this->hashTable->put('key1', 'value1');
        $this->hashTable->put('key2', 'value2');
        $this->hashTable->put('key3', 'value3');

        $entries = [];
        foreach ($this->hashTable as $key => $value) {
            $entries[$key] = $value;
        }

        $this->assertCount(3, $entries);
        $this->assertEquals('value1', $entries['key1']);
        $this->assertEquals('value2', $entries['key2']);
        $this->assertEquals('value3', $entries['key3']);
    }

    /**
     * Test Countable interface
     */
    public function testCountable(): void
    {
        $this->hashTable->put('key1', 'value1');
        $this->hashTable->put('key2', 'value2');

        // Should work with count()
        $this->assertEquals(2, count($this->hashTable));
    }

    /**
     * Test IteratorAggregate interface
     */
    public function testIteratorAggregate(): void
    {
        $this->hashTable->put('name', 'John');
        $this->hashTable->put('age', 30);

        $iterator = $this->hashTable->getIterator();
        $this->assertNotNull($iterator);

        $data = iterator_to_array($iterator);
        $this->assertEquals('John', $data['name']);
        $this->assertEquals(30, $data['age']);
    }

    /**
     * Test with object as value
     */
    public function testObjectAsValue(): void
    {
        $obj = new \stdClass();
        $obj->name = 'Test';

        $this->hashTable->put('object', $obj);
        $retrieved = $this->hashTable->get('object');

        $this->assertInstanceOf(\stdClass::class, $retrieved);
        $this->assertEquals('Test', $retrieved->name);
    }

    /**
     * Test large dataset
     */
    public function testLargeDataset(): void
    {
        for ($i = 0; $i < 1000; $i++) {
            $this->hashTable->put("key_$i", "value_$i");
        }

        $this->assertEquals(1000, $this->hashTable->count());

        // Verify random entries
        $this->assertEquals('value_0', $this->hashTable->get('key_0'));
        $this->assertEquals('value_500', $this->hashTable->get('key_500'));
        $this->assertEquals('value_999', $this->hashTable->get('key_999'));
    }

    /**
     * Test empty keys list
     */
    public function testEmptyKeysList(): void
    {
        $this->assertEmpty($this->hashTable->keys());
    }

    /**
     * Test empty values list
     */
    public function testEmptyValuesList(): void
    {
        $this->assertEmpty($this->hashTable->values());
    }

    /**
     * Test collision statistics
     */
    public function testCollisionStatistics(): void
    {
        for ($i = 0; $i < 10; $i++) {
            $this->hashTable->put("key_$i", "value_$i");
        }

        $stats = $this->hashTable->getCollisionStats();

        $this->assertIsArray($stats);
        $this->assertArrayHasKey('capacity', $stats);
        $this->assertArrayHasKey('size', $stats);
        $this->assertArrayHasKey('load_factor', $stats);
        $this->assertArrayHasKey('collision_buckets', $stats);
        $this->assertEquals(10, $stats['size']);
    }

    /**
     * Test sequential key insertion and retrieval
     */
    public function testSequentialOperations(): void
    {
        $data = [
            'username' => 'alice',
            'email' => 'alice@example.com',
            'age' => 25,
            'active' => true,
        ];

        foreach ($data as $key => $value) {
            $this->hashTable->put($key, $value);
        }

        foreach ($data as $key => $value) {
            $this->assertEquals($value, $this->hashTable->get($key));
        }

        $this->assertEquals(count($data), $this->hashTable->count());
    }

    /**
     * Test that removing and re-adding works correctly
     */
    public function testRemoveAndReadd(): void
    {
        $this->hashTable->put('key', 'value1');
        $this->assertEquals('value1', $this->hashTable->get('key'));

        $this->hashTable->remove('key');
        $this->assertNull($this->hashTable->get('key'));

        $this->hashTable->put('key', 'value2');
        $this->assertEquals('value2', $this->hashTable->get('key'));
    }
}
