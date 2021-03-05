<?php

namespace Kellegous;

use Kellegous\LinkedList\Node;

class LinkedList
{
    private ?Node $head = null;
    private ?Node $tail = null;

    public function isEmpty(): bool
    {
        return $this->head === null;
    }

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

    public function iterate(): \Iterator
    {
        $node = $this->head;
        while ($node !== null) {
            yield $node->getValue();
            $node = $node->getNext();
        }
    }
}