<?php

namespace Algoritmes\Objects;

/**
 * Binary tree node met left en right child references.
 * Gebruikt voor Binary Search Tree implementatie.
 */
final class BinaryNode
{
    public mixed $value;
    public ?BinaryNode $left = null;
    public ?BinaryNode $right = null;

    public function __construct(mixed $value)
    {
        $this->value = $value;
    }
}
