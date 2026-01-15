<?php

namespace Algoritmes\Algoritmes;

use Algoritmes\Interfaces\IsComparator;
use Algoritmes\Objects\BinaryNode;


final class BinarySearchTree
{
    private ?BinaryNode $root = null;
    private int $size = 0;
    private IsComparator $comparator;

    public function __construct(IsComparator $comparator)
    {
        $this->comparator = $comparator;
    }

    public function insert(mixed $value): void
    {
        $this->root = $this->insertRecursive($this->root, $value);
    }

    private function insertRecursive(?BinaryNode $node, mixed $value): BinaryNode
    {
        // Base case: lege positie gevonden
        if ($node === null) {
            $this->size++;
            return new BinaryNode($value);
        }

        $comparison = $this->comparator->compare($value, $node->value);

        if ($comparison < 0) {
            // Waarde is kleiner, ga naar links
            $node->left = $this->insertRecursive($node->left, $value);
        } elseif ($comparison > 0) {
            // Waarde is groter, ga naar rechts
            $node->right = $this->insertRecursive($node->right, $value);
        }
        // Als comparison === 0: duplicate, negeer (size blijft gelijk)

        return $node;
    }

    public function find(mixed $value): ?BinaryNode
    {
        return $this->findRecursive($this->root, $value);
    }

    private function findRecursive(?BinaryNode $node, mixed $value): ?BinaryNode
    {
        // Base case: niet gevonden of gevonden
        if ($node === null) {
            return null;
        }

        $comparison = $this->comparator->compare($value, $node->value);

        if ($comparison === 0) {
            return $node;  // Gevonden!
        } elseif ($comparison < 0) {
            return $this->findRecursive($node->left, $value);  // Zoek links
        } else {
            return $this->findRecursive($node->right, $value);  // Zoek rechts
        }
    }

    public function findMin(): mixed
    {
        if ($this->root === null) {
            return null;
        }

        $node = $this->root;
        while ($node->left !== null) {
            $node = $node->left;
        }
        return $node->value;
    }

    public function findMax(): mixed
    {
        if ($this->root === null) {
            return null;
        }

        $node = $this->root;
        while ($node->right !== null) {
            $node = $node->right;
        }
        return $node->value;
    }

    public function remove(mixed $value): void
    {
        $this->root = $this->removeRecursive($this->root, $value);
    }
    private function removeRecursive(?BinaryNode $node, mixed $value): ?BinaryNode
    {
        if ($node === null) {
            return null;  // Niet gevonden
        }

        $comparison = $this->comparator->compare($value, $node->value);

        if ($comparison < 0) {
            // Zoek in left subtree
            $node->left = $this->removeRecursive($node->left, $value);
        } elseif ($comparison > 0) {
            // Zoek in right subtree
            $node->right = $this->removeRecursive($node->right, $value);
        } else {
            // Node gevonden, verwijder deze

            // Case 1: Geen kinderen (leaf)
            if ($node->left === null && $node->right === null) {
                $this->size--;
                return null;
            }

            // Case 2a: Alleen right child
            if ($node->left === null) {
                $this->size--;
                return $node->right;
            }

            // Case 2b: Alleen left child
            if ($node->right === null) {
                $this->size--;
                return $node->left;
            }

            // Case 3: Twee kinderen
            // Vind kleinste node in right subtree (inorder successor)
            $minNode = $this->findMinNode($node->right);

            // Vervang de waarde van de huidige node
            $node->value = $minNode->value;

            // Verwijder de inorder successor (heeft max 1 kind)
            $node->right = $this->removeRecursive($node->right, $minNode->value);
        }

        return $node;
    }

    private function findMinNode(BinaryNode $node): BinaryNode
    {
        while ($node->left !== null) {
            $node = $node->left;
        }
        return $node;
    }

    public function inOrder(): array
    {
        $result = [];
        $this->inOrderRecursive($this->root, $result);
        return $result;
    }

    private function inOrderRecursive(?BinaryNode $node, array &$result): void
    {
        if ($node === null) {
            return;
        }

        $this->inOrderRecursive($node->left, $result);
        $result[] = $node->value;
        $this->inOrderRecursive($node->right, $result);
    }

    public function preOrder(): array
    {
        $result = [];
        $this->preOrderRecursive($this->root, $result);
        return $result;
    }

    private function preOrderRecursive(?BinaryNode $node, array &$result): void
    {
        if ($node === null) {
            return;
        }

        $result[] = $node->value;
        $this->preOrderRecursive($node->left, $result);
        $this->preOrderRecursive($node->right, $result);
    }

    public function postOrder(): array
    {
        $result = [];
        $this->postOrderRecursive($this->root, $result);
        return $result;
    }

    private function postOrderRecursive(?BinaryNode $node, array &$result): void
    {
        if ($node === null) {
            return;
        }

        $this->postOrderRecursive($node->left, $result);
        $this->postOrderRecursive($node->right, $result);
        $result[] = $node->value;
    }

    public function size(): int
    {
        return $this->size;
    }

    public function isEmpty(): bool
    {
        return $this->size === 0;
    }

    public function clear(): void
    {
        $this->root = null;
        $this->size = 0;
    }

    public function height(): int
    {
        return $this->heightRecursive($this->root);
    }

    private function heightRecursive(?BinaryNode $node): int
    {
        if ($node === null) {
            return -1;
        }

        $leftHeight = $this->heightRecursive($node->left);
        $rightHeight = $this->heightRecursive($node->right);

        return 1 + max($leftHeight, $rightHeight);
    }
}
