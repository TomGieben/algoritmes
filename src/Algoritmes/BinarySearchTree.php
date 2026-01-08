<?php

namespace Algoritmes\Algoritmes;

use Algoritmes\Interfaces\IsComparator;
use Algoritmes\Objects\BinaryNode;

/**
 * Binary Search Tree implementatie volgens school specificaties.
 * 
 * Eigenschappen:
 * - Elke node is een key naar een item
 * - Nodes in left subtree bevatten kleinere keys
 * - Nodes in right subtree bevatten grotere keys
 * - Elke key komt maar één keer voor
 * 
 * Operaties: find, findMin, findMax, insert, remove
 * Runtime: O(log N) voor balanced tree, O(N) worst case
 */
final class BinarySearchTree
{
    private ?BinaryNode $root = null;
    private int $size = 0;
    private IsComparator $comparator;

    public function __construct(IsComparator $comparator)
    {
        $this->comparator = $comparator;
    }

    /**
     * Voeg een waarde toe aan de tree.
     * Duplicate values worden genegeerd (zoals school specificatie).
     */
    public function insert(mixed $value): void
    {
        $this->root = $this->insertRecursive($this->root, $value);
    }

    /**
     * Recursive helper voor insert operatie.
     */
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

    /**
     * Zoek een waarde in de tree.
     * Returns de node als gevonden, null als niet gevonden.
     */
    public function find(mixed $value): ?BinaryNode
    {
        return $this->findRecursive($this->root, $value);
    }

    /**
     * Recursive helper voor find operatie.
     */
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

    /**
     * Vind de minimale waarde in de tree.
     * Returns null als tree leeg is.
     */
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

    /**
     * Vind de maximale waarde in de tree.
     * Returns null als tree leeg is.
     */
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

    /**
     * Verwijder een waarde uit de tree.
     * Implementeert de 3 cases:
     * 1. Geen kinderen (leaf): verwijder node
     * 2. Één kind: vervang door kind
     * 3. Twee kinderen: vervang door kleinste waarde in right subtree
     */
    public function remove(mixed $value): void
    {
        $this->root = $this->removeRecursive($this->root, $value);
    }

    /**
     * Recursive helper voor remove operatie.
     */
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

    /**
     * Helper: vind de node met minimale waarde in een subtree.
     */
    private function findMinNode(BinaryNode $node): BinaryNode
    {
        while ($node->left !== null) {
            $node = $node->left;
        }
        return $node;
    }

    /**
     * In-order traversal: left, node, right
     * Geeft gesorteerde volgorde voor BST.
     */
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

    /**
     * Pre-order traversal: node, left, right
     * Geschikt voor copying trees.
     */
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

    /**
     * Post-order traversal: left, right, node
     * Geschikt voor deleting trees.
     */
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

    /**
     * Aantal nodes in de tree.
     */
    public function size(): int
    {
        return $this->size;
    }

    /**
     * Check of tree leeg is.
     */
    public function isEmpty(): bool
    {
        return $this->size === 0;
    }

    /**
     * Verwijder alle nodes uit de tree.
     */
    public function clear(): void
    {
        $this->root = null;
        $this->size = 0;
    }

    /**
     * Bereken de hoogte van de tree.
     * Empty tree heeft hoogte -1, single node heeft hoogte 0.
     */
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
