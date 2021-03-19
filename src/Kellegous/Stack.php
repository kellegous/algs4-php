<?php

namespace Kellegous;

use Kellegous\Bag\Node;

class Stack
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
     * @param mixed $item
     */
    public function push($item)
    {
        $this->head = new Node(
            $item,
            $this->head
        );
        $this->n++;
    }

    /**
     * @return mixed
     *
     * @throws NoSuchElementException
     */
    public function pop()
    {
        if ($this->isEmpty()) {
            throw new NoSuchElementException("Stack underflow");
        }
        $head = $this->head;
        $this->head = $head->getNext();
        $this->n--;
        return $head->getItem();
    }

    /**
     * @return mixed
     *
     * @throws NoSuchElementException
     */
    public function peek()
    {
        if ($this->isEmpty()) {
            throw new NoSuchElementException("Stack underflow");
        }
        return $this->head->getItem();
    }

    /**
     * @return \Iterator
     */
    public function iterate(): \Iterator
    {
        for ($curr = $this->head; $curr !== null; $curr = $curr->getNext()) {
            yield $curr->getItem();
        }
    }
}