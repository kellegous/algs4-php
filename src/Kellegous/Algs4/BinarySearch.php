<?php declare(strict_types=1);

namespace Kellegous\Algs4;

/**
 * The {@code BinarySearch} class provides a static method for binary searching for values in a sorted array of values,
 * where order is determined by a $compare function.
 *
 * <p>
 * The <em>indexOf</em> and <em>search</em> operations take logarithmic time in the worst case.
 * <p>
 *
 * For additional documentation, see <a href="https://algs4.cs.princeton.edu/11model">Section 1.1</a> of
 * <i>Algorithms, 4th Edition</i> by Robert Sedgewick and Kevin Wayne.
 *
 * @author Robert Sedgewick
 * @author Kevin Wayne
 * @author Kelly Norton
 */
final class BinarySearch
{
    private function __construct()
    {
    }

    /**
     * Returns the index of the specified key in the specified array.
     *
     * @template T
     *
     * @param T[] $a
     * @param T $key
     * @param \Closure(T, T):int|null $compare
     *
     * @return int
     */
    public static function indexOf(
        array     $a,
        mixed     $key,
        ?\Closure $compare = null
    ): int
    {
        $compare ??= function (mixed $a, mixed $b): int {
            return $a <=> $b;
        };

        $lo = 0;
        $hi = count($a) - 1;
        while ($lo <= $hi) {
            $mid = $lo + (($hi - $lo) >> 1);
            $cmp = $compare($key, $a[$mid]);
            if ($cmp < 0) {
                $hi = $mid - 1;
            } else if ($cmp > 0) {
                $lo = $mid + 1;
            } else {
                return $mid;
            }
        }
        return -1;
    }

    /**
     * Uses binary search to find and return the smallest index i in [0, n) at which $compare($key, $a[$i]) >= 0. This
     * method uses the strategy present in the Search function of Go's standard sort package.
     *
     * @template T
     *
     * @param T[] $a
     * @param T $key
     * @param \Closure(T, T):int|null $compare
     *
     * @return int
     *
     * @see https://pkg.go.dev/sort#Search
     */
    public static function search(
        array     $a,
        mixed     $key,
        ?\Closure $compare = null
    ): int
    {
        $compare ??= function (mixed $a, mixed $b): int {
            return $a <=> $b;
        };

        $lo = 0;
        $hi = count($a) - 1;
        while ($lo < $hi) {
            $mid = $lo + (($hi - $lo) >> 1);
            $cmp = $compare($key, $a[$mid]);
            if ($cmp >= 0) {
                $lo = $mid + 1;
            } else {
                $hi = $mid;
            }
        }
        return $lo;
    }
}