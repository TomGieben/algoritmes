<?php

namespace Tests\Algoritmes;

use PHPUnit\Framework\TestCase;
use Algoritmes\Algoritmes\BinarySearchTree;
use Algoritmes\Helpers\CsvLoader;
use Algoritmes\Interfaces\IsComparator;

class IntComparator implements IsComparator
{
    public function compare(mixed $a, mixed $b): int
    {
        return $a <=> $b;
    }
}

class BinarySearchTreeTest extends TestCase
{
    private BinarySearchTree $bst;
    private array $primes;

    protected function setUp(): void
    {
        $this->bst = new BinarySearchTree(new IntComparator());
        $this->primes = CsvLoader::loadColumnToArray('storage/1m.csv', 1, true);
    }

    public function testInsertAndFind(): void
    {
        // Voeg enkele waarden toe
        $this->bst->insert(50);
        $this->bst->insert(30);
        $this->bst->insert(70);
        $this->bst->insert(20);
        $this->bst->insert(40);
        $this->bst->insert(60);
        $this->bst->insert(80);

        $this->assertEquals(7, $this->bst->size());

        // Test find
        $node = $this->bst->find(50);
        $this->assertNotNull($node);
        $this->assertEquals(50, $node->value);

        $node = $this->bst->find(20);
        $this->assertNotNull($node);
        $this->assertEquals(20, $node->value);

        $node = $this->bst->find(100);
        $this->assertNull($node);
    }

    public function testDuplicatesAreIgnored(): void
    {
        $this->bst->insert(50);
        $this->bst->insert(50);
        $this->bst->insert(50);

        $this->assertEquals(1, $this->bst->size());
    }

    public function testFindMinAndMax(): void
    {
        $this->bst->insert(50);
        $this->bst->insert(30);
        $this->bst->insert(70);
        $this->bst->insert(20);
        $this->bst->insert(80);

        $this->assertEquals(20, $this->bst->findMin());
        $this->assertEquals(80, $this->bst->findMax());
    }

    public function testFindMinMaxEmptyTree(): void
    {
        $this->assertNull($this->bst->findMin());
        $this->assertNull($this->bst->findMax());
    }

    public function testRemoveLeafNode(): void
    {
        // Case 1: Geen kinderen (leaf)
        $this->bst->insert(50);
        $this->bst->insert(30);
        $this->bst->insert(70);
        $this->bst->insert(20);

        $this->bst->remove(20);
        $this->assertEquals(3, $this->bst->size());
        $this->assertNull($this->bst->find(20));
    }

    public function testRemoveNodeWithOneChild(): void
    {
        // Case 2: Eén kind
        $this->bst->insert(50);
        $this->bst->insert(30);
        $this->bst->insert(70);
        $this->bst->insert(60);

        $this->bst->remove(70);  // 70 heeft alleen left child (60)
        $this->assertEquals(3, $this->bst->size());
        $this->assertNull($this->bst->find(70));
        $this->assertNotNull($this->bst->find(60));
    }

    public function testRemoveNodeWithTwoChildren(): void
    {
        // Case 3: Twee kinderen
        $this->bst->insert(50);
        $this->bst->insert(30);
        $this->bst->insert(70);
        $this->bst->insert(20);
        $this->bst->insert(40);
        $this->bst->insert(60);
        $this->bst->insert(80);

        $this->bst->remove(50);  // Root met twee kinderen
        $this->assertEquals(6, $this->bst->size());
        $this->assertNull($this->bst->find(50));

        // Tree moet nog steeds geldig zijn
        $this->assertNotNull($this->bst->find(30));
        $this->assertNotNull($this->bst->find(70));
        $this->assertNotNull($this->bst->find(60));
    }

    public function testInOrderTraversal(): void
    {
        // In-order moet gesorteerde volgorde geven
        $this->bst->insert(50);
        $this->bst->insert(30);
        $this->bst->insert(70);
        $this->bst->insert(20);
        $this->bst->insert(40);
        $this->bst->insert(60);
        $this->bst->insert(80);

        $result = $this->bst->inOrder();
        $this->assertEquals([20, 30, 40, 50, 60, 70, 80], $result);
    }

    public function testPreOrderTraversal(): void
    {
        $this->bst->insert(50);
        $this->bst->insert(30);
        $this->bst->insert(70);
        $this->bst->insert(20);
        $this->bst->insert(40);

        $result = $this->bst->preOrder();
        $this->assertEquals([50, 30, 20, 40, 70], $result);
    }

    public function testPostOrderTraversal(): void
    {
        $this->bst->insert(50);
        $this->bst->insert(30);
        $this->bst->insert(70);
        $this->bst->insert(20);
        $this->bst->insert(40);

        $result = $this->bst->postOrder();
        $this->assertEquals([20, 40, 30, 70, 50], $result);
    }

    public function testHeight(): void
    {
        // Empty tree
        $this->assertEquals(-1, $this->bst->height());

        // Single node
        $this->bst->insert(50);
        $this->assertEquals(0, $this->bst->height());

        // Balanced tree
        $this->bst->insert(30);
        $this->bst->insert(70);
        $this->assertEquals(1, $this->bst->height());

        $this->bst->insert(20);
        $this->bst->insert(40);
        $this->assertEquals(2, $this->bst->height());
    }

    public function testClear(): void
    {
        $this->bst->insert(50);
        $this->bst->insert(30);
        $this->bst->insert(70);

        $this->bst->clear();
        $this->assertEquals(0, $this->bst->size());
        $this->assertTrue($this->bst->isEmpty());
        $this->assertNull($this->bst->find(50));
    }

    public function testBestCase(): void
    {
        // Best case: balanced tree door willekeurige volgorde
        // Neem subset van primes voor snellere tests
        $testData = array_slice($this->primes, 0, 1000);
        shuffle($testData);

        foreach ($testData as $prime) {
            $this->bst->insert((int)$prime);
        }

        // Controleer dat alle waarden gevonden kunnen worden
        $middleValue = $testData[count($testData) / 2];
        $this->assertNotNull($this->bst->find((int)$middleValue));
        $this->assertEquals(1000, $this->bst->size());
    }

    public function testWorstCase(): void
    {
        // Worst case: gesorteerde invoer creëert unbalanced tree (linked list)
        // Dit resulteert in O(N) operaties
        $testData = array_slice($this->primes, 0, 100);

        foreach ($testData as $prime) {
            $this->bst->insert((int)$prime);
        }

        // Tree is nu een linked list (alleen right children)
        // Height zou bijna gelijk moeten zijn aan size
        $this->assertEquals(100, $this->bst->size());
        $this->assertGreaterThan(90, $this->bst->height());

        // Zoeken naar laatste element kost nu O(N)
        $lastElement = (int)$testData[count($testData) - 1];
        $this->assertNotNull($this->bst->find($lastElement));
    }
}
