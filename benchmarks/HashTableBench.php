<?php

use Algoritmes\Algoritmes\HashTable;

class HashTableBench
{
    private $table;
    private $collisionTable;
    private $size = 100;

    public function setUp()
    {
        $this->table = new HashTable(101);
        // Fill with random keys
        for ($i = 0; $i < $this->size; $i++) {
            $this->table->set("key_$i", $i);
        }

        // Force collisions: keys that hash to same index.
        // HashTable uses crc32($key) % size.
        // We can just use a small size to force collisions naturally, or just test "Many items" vs "Few items".
        // Let's simulate worst case by having a table where we access an item in a full bucket.
        // But since we can't easily control the hash function from outside without mocking, 
        // we will rely on the fact that searching a full table is slower than an empty one.
    }

    /**
     * Best Case: Set/Get in empty table or no collision.
     * 
     * @Revs(1000)
     * @Iterations(5)
     */
    public function benchBestCase()
    {
        $table = new HashTable(101);
        $table->set("key", "value");
        $table->get("key");
    }

    /**
     * Worst Case: Get item from a populated table (potential collisions).
     * 
     * @Revs(1000)
     * @Iterations(5)
     * @BeforeMethods({"setUp"})
     */
    public function benchWorstCase()
    {
        // Accessing a key in a populated table
        $this->table->get("key_50");
    }
}
