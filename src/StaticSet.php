<?php

declare(strict_types=1);

namespace Kellegous\Algs4;

use Closure;
use SplFixedArray;

/**
 * @template T
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
final readonly class StaticSet
{
    /**
     * @var SplFixedArray<T>
     */
    private SplFixedArray $keys;

    /**
     * @param T[] $keys
     * @param Closure(T, T): int $comparator
     */
    public function __construct(
        array $keys,
        private Closure $comparator
    ) {
        $this->keys = self::getUniqueKeys($keys, $comparator);
    }

    /**
     * @param array<T> $keys
     * @param Closure(T,T):int $cmp
     * @return SplFixedArray<T>
     */
    private static function getUniqueKeys(
        array $keys,
        Closure $cmp
    ): SplFixedArray {
        if (empty($keys)) {
            return new SplFixedArray(0);
        }

        usort($keys, $cmp);
        $count = 1;
        for ($i = 1, $n = count($keys); $i < $n; $i++) {
            if ($cmp($keys[$i], $keys[$i - 1]) !== 0) {
                $count++;
            }
        }
        $unique = new SplFixedArray($count);
        $unique[0] = $keys[0];
        for ($i = 1, $j = 1, $n = count($keys); $i < $n; $i++) {
            if ($cmp($keys[$i], $keys[$i - 1]) !== 0) {
                $unique[$j] = $keys[$i];
                $j++;
            }
        }
        return $unique;
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
            assert(!is_null($val));
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