<?php

declare(strict_types=1);

namespace Kellegous\Algs4;

use InvalidArgumentException;

/**
 *  The {@code StaticSeTofInts} class represents a set of integers.
 *  It supports searching for a given integer is in the set. It accomplishes
 *  this by keeping the set of integers in a sorted array and using
 *  binary search to find the given integer.
 *
 *  <p>
 *  The <em>rank</em> and <em>contains</em> operations take
 *  logarithmic time in the worst case.
 *  <p>
 *
 *  For additional documentation, see <a href="https://algs4.cs.princeton.edu/12oop">Section 1.2</a> of
 *  <i>Algorithms, 4th Edition</i> by Robert Sedgewick and Kevin Wayne.
 *
 * @author Robert Sedgewick
 * @author Kevin Wayne
 * @author Kelly Norton
 */
final class StaticSetOfInts
{
    /**
     * @var int[]
     */
    private array $keys;

    /**
     * Initializes a set of integers specified by the integer array.
     * @param int[] $keys the array of integers
     * @throws InvalidArgumentException if the array contains duplicate integers
     */
    public function __construct(array $keys)
    {
        // NOTE(knorton): The code in the text ignores duplicates in the input
        // array. The source in the repo, though, throws an exception if a duplicate is
        // present. Based on the examples, I'm pretty sure duplicates should be accepted.
        // largeAllowlist.txt, for example, contains duplicates.
        sort($keys, SORT_NUMERIC);
        $this->keys = iterator_to_array(self::uniqueKeys($keys));
    }

    /**
     * @param int[] $keys
     * @return iterable<int>
     */
    private static function uniqueKeys(array $keys): iterable
    {
        if (empty($keys)) {
            return;
        }

        yield $keys[0];
        for ($i = 1, $n = count($keys); $i < $n; $i++) {
            if ($keys[$i] !== $keys[$i - 1]) {
                yield $keys[$i];
            }
        }
    }

    /**
     * Is the key in this set of integers?
     *
     * @param int $key the search key
     * @return bool true if the set of integers contains the key; false otherwise
     */
    public function contains(int $key): bool
    {
        return $this->rank($key) !== -1;
    }

    /**
     * Returns either the index of the search key in the sorted array
     * (if the key is in the set) or -1 (if the key is not in the set).
     *
     * @param int $key the search key
     * @return int the number of keys in this set less than the key (if the key is in the set)
     * or -1 (if the key is not in the set).
     */
    public function rank(int $key): int
    {
        $lo = 0;
        $hi = count($this->keys) - 1;
        while ($lo <= $hi) {
            $mid = $lo + intval(($hi - $lo) / 2);
            $val = $this->keys[$mid];
            if ($key < $val) {
                $hi = $mid - 1;
            } elseif ($key > $val) {
                $lo = $mid + 1;
            } else {
                return $mid;
            }
        }
        return -1;
    }
}