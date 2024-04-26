<?php
declare(strict_types=1);

namespace Kellegous\Algs4\Bag;

/**
 * @template T
 */
final class Node
{
    /**
     * @var T
     */
    private mixed $item;

    /**
     * @var Node<T>|null
     */
    private ?Node $next;

    /**
     * @param T $item
     * @param Node<T>|null $next
     */
    public function __construct(mixed $item, ?Node $next)
    {
        $this->item = $item;
        $this->next = $next;
    }

    /**
     * @return T
     */
    public function getItem(): mixed
    {
        return $this->item;
    }

    /**
     * @return Node<T>|null
     */
    public function getNext(): ?Node
    {
        return $this->next;
    }

    /**
     * @param Node<T>|null $next
     *
     * @return void
     */
    public function setNext(?Node $next): void
    {
        $this->next = $next;
    }
}