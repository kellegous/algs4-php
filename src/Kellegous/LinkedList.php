<?php

namespace Kellegous;

use Kellegous\LinkedList\Node;

class LinkedList
{
    private ?Node $head = null;
    private ?Node $tail = null;

    /**
     * @return bool
     */
    public function isEmpty(): bool
    {
        return $this->head === null;
    }

    /**
     * @param $value
     *
     * @return $this
     */
    public function pushBack($value): LinkedList
    {
        $node = new Node($value);
        if ($this->isEmpty()) {
            $this->head = $node;
            $this->tail = $node;
        } else {
            $this->tail->setNext($node);
            $node->setPrev($this->tail);
            $this->tail = $node;
        }
        return $this;
    }

    /**
     * @param $value
     *
     * @return $this
     */
    public function pushFront($value): LinkedList
    {
        $node = new Node($value);
        if ($this->isEmpty()) {
            $this->head = $node;
            $this->tail = $node;
        } else {
            $this->head->setPrev($node);
            $node->setNext($this->head);
            $this->head = $node;
        }
        return $this;
    }

    /**
     * @return mixed
     *
     * @throws NoSuchElementException
     */
    public function popFront()
    {
        if ($this->isEmpty()) {
            throw new NoSuchElementException();
        }

        $node = $this->head;
        if ($this->head === $this->tail) {
            $this->head = null;
            $this->tail = null;
        } else {
            $this->head = $this->head->getNext();
            $this->head->setPrev(null);
        }

        return $node->getValue();
    }

    /**
     * @return mixed
     *
     * @throws NoSuchElementException
     */
    public function popBack()
    {
        if ($this->isEmpty()) {
            throw new NoSuchElementException();
        }

        $node = $this->tail;
        if ($this->head === $this->tail) {
            $this->head = null;
            $this->tail = null;
        } else {
            $this->tail = $this->tail->getPrev();
            $this->tail->setNext(null);
        }

        return $node->getValue();
    }

    /**
     * @return \Iterator
     */
    public function iterate(): \Iterator
    {
        $node = $this->head;
        while ($node !== null) {
            yield $node->getValue();
            $node = $node->getNext();
        }
    }
}