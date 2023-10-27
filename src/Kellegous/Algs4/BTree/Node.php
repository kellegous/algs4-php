<?php

namespace Kellegous\Algs4\BTree;

use Kellegous\Algs4\BTree;
use Kellegous\Algs4\T;

/**
 * @template K extends \Comparable
 * @template V
 */
final class Node
{
    public int $m;

    /**
     * @var \SplFixedArray<Entry<K, V>> $children
     */
    public \SplFixedArray $children;

    public function __construct(int $k)
    {
        $this->children = new \SplFixedArray(BTree::M);
        $this->m = $k;
    }

    /**
     * @param int $i
     * @return Entry<K, V>
     */
    public function getChild(int $i): Entry
    {
        return T::notNull($this->children[$i]);
    }
}