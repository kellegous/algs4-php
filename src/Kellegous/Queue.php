<?php

namespace Kellegous;

use Kellegous\Bag\Node;

class Queue implements \Countable
{
    /**
     * @var Node|null
     */
    private $head = null;

    /**
     * @var Node|null
     */
    private $tail = null;

    /**
     * @var int
     */
    private $n = 0;

    public function isEmpty(): bool
    {
        return $this->head === null;
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return $this->n;
    }

    /**
     * @return mixed
     *
     * @throws NoSuchElementException
     */
    public function peek()
    {
        if ($this->isEmpty()) {
            throw new NoSuchElementException("Queue underflow");
        }
        return $this->head->getItem();
    }

    /**
     * @param mixed $item
     */
    public function enqueue($item)
    {
        $node = new Node($item, null);
        if ($this->isEmpty()) {
            $this->head = $node;
            $this->tail = $node;
        } else {
            $this->tail->setNext($node);
            $this->tail = $node;
        }
        $this->n++;
    }

    /**
     * @return mixed
     *
     * @throws NoSuchElementException
     */
    public function dequeue()
    {
        if ($this->isEmpty()) {
            throw new NoSuchElementException("Queue underflow");
        }

        $head = $this->head;
        $this->head = $head->getNext();
        if ($this->isEmpty()) {
            $this->tail = null;
        }
        $this->n--;
        return $head->getItem();
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