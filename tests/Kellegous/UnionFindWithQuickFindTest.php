<?php

namespace Kellegous;

use Kellegous\Testing\UnionFindTests;
use PHPUnit\Framework\TestCase;

class UnionFindWithQuickFindTest extends TestCase
{
    use UnionFindTests;

    public static function create(int $n): UnionFind
    {
        return new UnionFindWithQuickFind($n);
    }
}