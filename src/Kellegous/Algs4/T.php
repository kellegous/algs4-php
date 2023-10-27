<?php

namespace Kellegous\Algs4;

final class T
{
    /**
     * @template T
     * @param T|null $v
     * @return T
     */
    public static function notNull(mixed $v): mixed
    {
        assert($v !== null);
        return $v;
    }
}