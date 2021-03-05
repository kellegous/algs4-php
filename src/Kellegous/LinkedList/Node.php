<?php

namespace Kellegous\LinkedList;

class Node
{
    /**
     * @var mixed
     */
    private $value;

    private ?Node $next = null;

    private ?Node $prev = null;

    public function __construct(
        $value
    ) {
        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getNext(): ?Node
    {
        return $this->next;
    }

    public function getPrev(): ?Node
    {
        return $this->prev;
    }

    public function setNext(?Node $next) {
        $this->next = $next;
    }

    public function setPrev(?Node $prev) {
        $this->prev = $prev;
    }
}