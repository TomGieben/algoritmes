<?php

namespace Algoritmes\Objects;

class DoublyNode
{
    public mixed $data;
    public ?DoublyNode $next = null;
    public ?DoublyNode $prev = null;

    public function __construct(mixed $data)
    {
        $this->data = $data;
    }
}
