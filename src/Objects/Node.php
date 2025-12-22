<?php

namespace Algoritmes\Objects;

class Node
{
    public mixed $data;
    public ?Node $next = null;

    public function __construct(mixed $data)
    {
        $this->data = $data;
    }
}
