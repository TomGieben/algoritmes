<?php

namespace Algoritmes\Objects;

class DoublyNode
{
    public mixed $value;
    public ?DoublyNode $next = null;
    public ?DoublyNode $prev = null;

    public function __construct(mixed $value)
    {
        $this->value = $value;
    }
}
