<?php

namespace Kellegous\Bag;

class Node
{
    private $item;

    private $next;

    public function __construct(
        $item,
        ?Node $next
    ) {
        $this->item = $item;
        $this->next = $next;
    }

    public function getItem()
    {
        return $this->item;
    }

    public function getNext(): ?Node
    {
        return $this->next;
    }

    public function setNext(?Node $node)
    {
        $this->next = $node;
    }
}