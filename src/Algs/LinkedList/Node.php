<?php

namespace Algs\LinkedList;

class Node
{
    /**
     * @var mixed
     */
    private $value;

    private ?Node $next;

    private ?Node $prev;

    public function __construct(
        $value
    ) {
        $this->value = $value;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    public function getNext(): ?Node
    {
        return $this->next;
    }

    public function setNext(?Node $next)
    {
        $this->next = $next;
    }

    public function getPrev(): ?Node
    {
        return $this->prev;
    }

    public function setPrev(?Node $prev)
    {
        $this->prev = $prev;
    }

    public function getForwardIterator(): \Generator
    {
        for ($node = $this; $node !== null; $node = $node->getNext()) {
            yield $node;
        }
    }

    public function getBackwardIterator(): \Generator
    {
        for ($node = $this; $node != null; $node = $node->getPrev()) {
            yield $node;
        }
    }

    public function remove()
    {
        $prev = $this->prev;
        $next = $this->next;
        if ($prev !== null) {
            $prev->setNext($next);
        }
        if ($next !== null) {
            $next->setPrev($prev);
        }
    }
}