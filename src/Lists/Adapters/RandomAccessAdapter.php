<?php

declare(strict_types=1);

namespace Algoritmes\Lists\Adapters;

use Algoritmes\Lists\ArrayList;
use Algoritmes\Lists\Interfaces\ListInterface;
use Algoritmes\Lists\Interfaces\RandomAccessListInterface;

/**
 * Utility that ensures algorithms operate on a random-access capable list.
 */
final class RandomAccessAdapter
{
    /**
     * @return RandomAccessListInterface
     */
    public static function ensure(ListInterface $list): RandomAccessListInterface
    {
        if ($list instanceof RandomAccessListInterface) {
            return $list;
        }

        $buffer = new ArrayList('mixed');
        foreach ($list as $item) {
            $buffer->add($item);
        }

        return $buffer;
    }
}
