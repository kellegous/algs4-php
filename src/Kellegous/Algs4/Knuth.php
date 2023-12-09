<?php declare(strict_types=1);

namespace Kellegous\Algs4;

/**
 *  The {@code Knuth} class provides a client for shuffling an array of values
 *   using the Knuth (or Fisher-Yates)  shuffling algorithm. This algorithm guarantees to rearrange the
 *  elements in uniformly random order, under
 *  the assumption that the provided random_int function generates independent and
 *  uniformly distributed numbers between 0 and N.
 *  <p>
 *  For additional documentation,
 *  see <a href="https://algs4.cs.princeton.edu/11model">Section 1.1</a> of
 *  <i>Algorithms, 4th Edition</i> by Robert Sedgewick and Kevin Wayne.
 *  See {@link StdRandom} for versions that shuffle arrays and
 *  subarrays of objects, doubles, and ints.
 *
 * @author Robert Sedgewick
 * @author Kevin Wayne
 * @author Kelly Norton
 */
final class Knuth
{
    private function __construct()
    {
    }

    /**
     *  Rearranges an array of objects in uniformly random order
     *  (under the assumption that {@code $random_int} generates independent
     *  and uniformly distributed numbers between 0 and n).
     *
     * @template T
     * @param T[] $a
     * @param \Closure(int):int|null $random_int
     * @return T[]
     */
    public static function shuffle(
        array    $a,
        \Closure $random_int = null
    ): array
    {
        $random_int ??= fn(int $n) => random_int(0, $n);
        for ($i = 0, $n = count($a); $i < $n; $i++) {
            $r = $random_int($i);
            $swap = $a[$r];
            $a[$r] = $a[$i];
            $a[$i] = $swap;
        }
        return $a;
    }

    /**
     * Rearranges an array of objects in uniformly random order
     *  (under the assumption that {@code $random_int} generates independent
     *  and uniformly distributed numbers between 0 and n).
     *
     * @template T
     * @param T[] $a
     * @param \Closure(int):int|null $random_int
     * @return T[]
     */
    public static function shuffleAlternate(
        array    $a,
        \Closure $random_int = null
    ): array
    {
        $random_int ??= fn(int $n) => random_int(0, $n);
        for ($i = 0, $n = count($a); $i < $n; $i++) {
            $r = $i + $random_int($n - $i - 1);
            $swap = $a[$r];
            $a[$r] = $a[$i];
            $a[$i] = $swap;
        }
        return $a;
    }
}