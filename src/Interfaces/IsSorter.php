<?php

namespace Algoritmes\Interfaces;

use Algoritmes\Enums\SortDirection;

interface IsSorter
{
    public function sort(SortDirection $direction = SortDirection::ASCENDING): IsList;
}
