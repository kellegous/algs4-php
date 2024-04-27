<?php

declare(strict_types=1);

namespace Kellegous\Algs4;

/**
 *  The {@code Counter} class is a mutable data type to encapsulate a counter.
 *
 *  <p>
 *  For additional documentation,
 *  see <a href="https://algs4.cs.princeton.edu/12oop">Section 1.2</a> of
 *  <i>Algorithms, 4th Edition</i> by Robert Sedgewick and Kevin Wayne.
 * </p>
 *
 * @author Robert Sedgewick
 * @author Kevin Wayne
 * @author Kelly Norton
 */
final class Counter
{
    private int $count = 0;

    /**
     * @param string $name
     */
    public function __construct(
        private readonly string $name
    ) {
    }

    /**
     * Increments the counter by 1.
     *
     * @return void
     */
    public function increment(): void
    {
        $this->count++;
    }

    /**
     * Returns the current value of this counter.
     *
     * @return int
     */
    public function tally(): int
    {
        return $this->count;
    }

    /**
     * Returns the name of this counter.
     *
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * Returns a string representation of this counter.
     *
     * @return string
     */
    public function __toString(): string
    {
        return "{$this->count} {$this->name}";
    }

    /**
     * Compares this counter to the specified counter.
     *
     * @param Counter $a
     * @param Counter $b
     * @return int
     */
    public static function compare(Counter $a, Counter $b): int
    {
        return $a->count <=> $b->count;
    }
}