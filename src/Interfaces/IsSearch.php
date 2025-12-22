<?php

namespace Algoritmes\Interfaces;

interface IsSearch
{
    public function search(IsList $list, mixed $target): int;
}
