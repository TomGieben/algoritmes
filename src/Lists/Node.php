<?php

declare(strict_types=1);

namespace Algoritmes\Lists;

class Node
{
    public mixed $data;
    public ?Node $next = null;
    public ?Node $prev = null;

    public function __construct(mixed $data)
    {
        $this->data = $data;
    }
}
