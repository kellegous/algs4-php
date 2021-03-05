<?php

namespace Kellegous\LinkedList;

class Node
{
    private static ?Node $empty = null;

    /**
     * @var mixed
     */
    private $value;

    private ?Node $next;

    private function __construct(
        $value,
        ?Node $next
    ) {
        $this->value = $value;
        $this->next = $next;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getNext(): ?Node
    {
        return $this->next;
    }

    public function iterate(): \Iterator
    {
        $node = $this;
        while ($node !== self::empty()) {
            yield $node->getValue();
            $node = $node->getNext();
        }
    }

    public function append($value): Node
    {
        return $this->concat(
            self::create($value, self::empty())
        );
    }

    public function prepend($value): Node
    {
        return self::create($value, $this);
    }

    public function concat(
        Node $node
    ): Node {
        if ($this === self::empty()) {
            return $node;
        }
        return self::create(
            $this->getValue(),
            $this->getNext()->concat($node)
        );
    }

    public static function create(
        $value,
        Node $next
    ): Node {
        return new self($value, $next);
    }

    public static function empty(): Node
    {
        if (self::$empty === null) {
            self::$empty = new Node(null, null);
        }
        return self::$empty;
    }

    public static function fromArray(array $values): Node
    {
        $node = self::empty();
        for ($i = count($values) - 1; $i >= 0; $i--) {
            $node = $node->prepend($values[$i]);
        }
        return $node;
    }
}