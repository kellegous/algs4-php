<?php

declare(strict_types=1);

namespace Kellegous\Algs4;

use Countable;
use Iterator;
use Kellegous\Algs4\Bag\Node;

/**
 * @template T
 * @implements Iterator<int, T>
 */
final class Bag implements Countable, Iterator
{
    /**
     * @var Node<T>|null
     */
    private ?Node $first = null;

    private int $n = 0;

    /**
     * @var Iterator<T>|null
     */
    private ?Iterator $iterator = null;

    /**
     * @param T $item
     *
     * @return void
     */
    public function add(mixed $item): void
    {
        $this->first = new Node($item, $this->first);
        $this->n++;
    }

    public function isEmpty(): bool
    {
        return $this->first === null;
    }

    /**
     * @return T|null
     */
    public function current(): mixed
    {
        return $this->iterator?->current();
    }

    public function next(): void
    {
        $this->iterator?->next();
    }

    public function key(): int
    {
        return $this->iterator?->key();
    }

    public function valid(): bool
    {
        return $this->iterator !== null && $this->iterator->valid();
    }

    public function rewind(): void
    {
        $this->iterator = $this->iterate();
    }

    public function count(): int
    {
        return $this->n;
    }

    /**
     * @return Iterator<T>
     */
    private function iterate(): Iterator
    {
        $current = $this->first;
        for (; $current !== null; $current = $current->getNext()) {
            yield $current->getItem();
        }
    }
}