<?php

declare(strict_types=1);

namespace Kellegous\Algs4;

use Closure;

/**
 * @template T int|string
 *
 * The {@code StaticSe} class represents a set of values.
 * It supports searching for a given value is in the set. It accomplishes
 * this by keeping the set of values in a sorted array and using
 * binary search to find the given integer.
 *
 * <p>
 * The <em>rank</em> and <em>contains</em> operations take
 * logarithmic time in the worst case.
 * <p>
 *
 * For additional documentation, see <a href="https://algs4.cs.princeton.edu/12oop">Section 1.2</a> of
 * <i>Algorithms, 4th Edition</i> by Robert Sedgewick and Kevin Wayne.
 *
 * @author Robert Sedgewick
 * @author Kevin Wayne
 * @author Kelly Norton
 */
final class StaticSet
{
    /**
     * @var T[]
     */
    private array $keys;

    /**
     * @var Closure(T, T): int
     */
    private Closure $comparator;

    /**
     * @param T[] $keys
     */
    public function __construct(array $keys)
    {
        $cmp = self::getComparator($keys);
        usort($keys, $cmp);
        $this->keys = iterator_to_array(self::uniqueKeys($keys, $cmp));
        $this->comparator = $cmp;
    }

    /**
     * @param T[] $keys
     * @param Closure(T, T): int $cmp
     * @return iterable<T>
     */
    private static function uniqueKeys(array $keys, Closure $cmp): iterable
    {
        if (empty($keys)) {
            return;
        }

        yield $keys[0];
        for ($i = 1, $n = count($keys); $i < $n; $i++) {
            if ($cmp($keys[$i], $keys[$i - 1]) !== 0) {
                yield $keys[$i];
            }
        }
    }

    /**
     * @param T[] $keys
     * @return Closure(T, T): int
     */
    private static function getComparator(array $keys): Closure
    {
        foreach ($keys as $key) {
            if (is_string($key)) {
                return fn($a, $b) => strcmp($a, $b);
            }
        }
        return fn($a, $b) => $a <=> $b;
    }

    /**
     * Is the key in this set of integers?
     *
     * @param T $key the search key
     * @return bool true if the set of integers contains the key; false otherwise
     */
    public function contains(mixed $key): bool
    {
        return $this->rank($key) !== -1;
    }

    /**
     * Returns either the index of the search key in the sorted array
     * (if the key is in the set) or -1 (if the key is not in the set).
     *
     * @param T $key the search key
     * @return int the number of keys in this set less than the key (if the key is in the set)
     * or -1 (if the key is not in the set).
     */
    public function rank(mixed $key): int
    {
        $lo = 0;
        $hi = count($this->keys) - 1;
        $fn = $this->comparator;
        while ($lo <= $hi) {
            $mid = $lo + intval(($hi - $lo) / 2);
            $val = $this->keys[$mid];
            $cmp = $fn($key, $val);
            if ($cmp < 0) {
                $hi = $mid - 1;
            } elseif ($cmp > 0) {
                $lo = $mid + 1;
            } else {
                return $mid;
            }
        }
        return -1;
    }
}