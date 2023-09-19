<?php declare(strict_types=1);

namespace Kellegous\Algs4;

use Kellegous\Algs4\Bag\Node;

/**
 * @template T
 * @implements \Iterator<int, T>
 */
final class Bag implements \Countable, \Iterator
{
    /**
     * @var Node<T>|null
     */
    private ?Node $first = null;

    private int $n = 0;

    /**
     * @var Node<T>|null
     */
    private ?Node $current = null;

    private int $key = 0;

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
        return $this->current?->getItem();
    }

    public function next(): void
    {
        if ($this->current === null) {
            return;
        }

        $this->current = $this->current->getNext();
        $this->key++;
    }

    public function key(): int
    {
        return $this->key;
    }

    public function valid(): bool
    {
        return $this->current !== null;
    }

    public function rewind(): void
    {
        $this->current = $this->first;
        $this->key = 0;
    }

    public function count(): int
    {
        return $this->n;
    }
}