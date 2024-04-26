<?php

namespace Kellegous\Algs4\BTree;

use Kellegous\Algs4\T;

/**
 * @template K extends \Comparable
 * @template V
 */
final class Entry
{
    /**
     * @var K
     */
    public mixed $key;

    /**
     * @var V|null
     */
    public mixed $val;

    /**
     * @var Node<K, V|null>|null
     */
    public ?Node $next;

    /**
     * @param K $key
     * @param V $val
     * @param Node<K, V|null>|null $next
     */
    public function __construct(mixed $key, mixed $val, ?Node $next)
    {
        $this->key = $key;
        $this->val = $val;
        $this->next = $next;
    }

    /**
     * @return Node<K, V|null>
     */
    public function mustHaveNext(): Node
    {
        return T::notNull($this->next);
    }
}