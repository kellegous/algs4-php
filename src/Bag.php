<?php

declare(strict_types=1);

namespace Kellegous\Algs4;

use Countable;
use IteratorAggregate;
use Kellegous\Algs4\Bag\Node;
use Override;
use Traversable;

/**
 * @template T
 * @implements IteratorAggregate<int, T>
 */
final class Bag implements Countable, IteratorAggregate
{
    /**
     * @var Node<T>|null
     */
    private ?Node $first = null;

    private int $n = 0;


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


    public function count(): int
    {
        return $this->n;
    }

    /**
     * @return Traversable<T>
     */
    #[Override]
    public function getIterator(): Traversable
    {
        $current = $this->first;
        for (; $current !== null; $current = $current->getNext()) {
            yield $current->getItem();
        }
    }
}