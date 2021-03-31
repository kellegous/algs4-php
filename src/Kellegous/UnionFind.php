<?php

namespace Kellegous;

interface UnionFind extends \Countable
{
    public function find(int $p): int;
    public function union(int $p, int $q);
}