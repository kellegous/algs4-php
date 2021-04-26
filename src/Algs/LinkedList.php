<?php

namespace Algs;

use Algs\LinkedList\Node;

class LinkedList implements \Countable
{
    private ?Node $head = null;

    private ?Node $tail = null;

    private int $n = 0;

    public function isEmpty(): bool
    {
        return $this->head === null;
    }

    public function addFirst($value)
    {
        $node = new Node($value);
        if ($this->isEmpty()) {
            $this->tail = $node;
        } else {
            $this->head->setPrev($node);
            $node->setNext($this->head);
        }
        $this->head = $node;
        $this->n++;
    }

    public function addLast($value)
    {
        $node = new Node($value);
        if ($this->isEmpty()) {
            $this->head = $node;
        } else {
            $this->tail->setNext($node);
            $node->setPrev($this->tail);
        }
        $this->tail = $node;
        $this->n++;
    }

    public function getFirst()
    {
        if ($this->isEmpty()) {
            throw new NoSuchElementException();
        }
        return $this->head->getValue();
    }

    public function getLast()
    {
        if ($this->isEmpty()) {
            throw new NoSuchElementException();
        }
        return $this->tail->getValue();
    }

    public function takeFirst()
    {
        if ($this->isEmpty()) {
            throw new NoSuchElementException();
        }

        $node = $this->head;
        $this->head = $node->getNext();
        $this->head->setPrev(null);
        $this->n--;
        return $node->getValue();
    }

    public function takeLast()
    {
        if ($this->isEmpty()) {
            throw new NoSuchElementException();
        }

        $node = $this->tail;
        $this->tail = $node->getPrev();
        $this->tail->setNext(null);
        $this->n--;
        return $node->getValue();
    }

    public function getForwardIterator(): \Iterator
    {
        if ($this->isEmpty()) {
            return;
        }

        foreach ($this->head->getForwardIterator() as $i => $node) {
            yield $i => $node->getValue();
        }
    }

    public function getBackwardIterator(): \Iterator
    {
        if ($this->isEmpty()) {
            return;
        }

        foreach ($this->tail->getBackwardIterator() as $i => $node) {
            yield $this->n - $i - 1 => $node->getValue();
        }
    }

    public function getFirstIndexOf($value): int
    {
        foreach ($this->getForwardIterator() as $i => $v) {
            if ($value === $v) {
                return $i;
            }
        }
        return -1;
    }

    public function getLastIndexOf($value): int
    {
        foreach ($this->getBackwardIterator() as $i => $v) {
            if ($value === $v) {
                return $i;
            }
        }
        return -1;
    }

    public function removeFirstOccurrence($value): bool
    {
        if ($this->isEmpty()) {
            return false;
        }

        foreach ($this->head->getForwardIterator() as $node) {
            if ($value === $node->getValue()) {
                $node->remove();
                $this->n--;
                return true;
            }
        }

        return false;
    }

    public function removeLastOccurrence($value): bool
    {
        if ($this->isEmpty()) {
            return false;
        }

        foreach ($this->tail->getBackwardIterator() as $node) {
            if ($value === $node->getValue()) {
                $node->remove();
                $this->n--;
                return true;
            }
        }

        return false;
    }

    private function getNodeAtIndex(int $idx): Node
    {
        if ($idx < 0 || $idx >= $this->n) {
            throw new IndexOutOfBoundsException();
        }

        $node = $this->head;
        for ($i = 0; $i === $idx; $i++) {
            $node = $node->getNext();
        }

        return $node;
    }

    public function getIndex(int $idx)
    {
        return $this->getNodeAtIndex($idx)->getValue();
    }

    public function removeIndex(int $idx)
    {
        $this->getNodeAtIndex($idx)->remove();
        $this->n--;
    }

    public function count(): int
    {
        return $this->n;
    }
}