<?php

namespace Kellegous;

use Kellegous\Bag\Node;

/**
 * @package Kellegous
 */
class Bag
{
    /**
     * @var Node|null
     */
    private $head = null;

    /**
     * @var int
     */
    private $n = 0;

    /**
     * @param mixed $item
     */
    public function add($item)
    {
        $this->head = new Node(
            $item,
            $this->head
        );
        $this->n++;
    }

    /**
     * @return bool
     */
    public function isEmpty(): bool
    {
        return $this->head === null;
    }

    /**
     * @return int
     */
    public function size(): int
    {
        return $this->n;
    }

    /**
     * @return \Iterator
     */
    public function iterate(): \Iterator
    {
        for ($curr = $this->head; $curr !== null; $curr = $curr->getNext()) {
            assert($curr instanceof Node);
            yield $curr->getItem();
        }
    }
}