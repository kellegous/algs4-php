<?php

declare(strict_types=1);

namespace Kellegous\Algs4;

use Closure;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class BinarySearchTest extends TestCase
{
    /**
     * @return iterable<array{array{}|array{mixed}, mixed, Closure(mixed, mixed):int|null, int}>
     */
    public static function indexOfTests(): iterable
    {
        yield [[], 0, null, -1];
        yield [[1], 0, null, -1];
        yield [[1], 1, null, 0];
        yield [[1, 3, 5], 1, null, 0];
        yield [[1, 3, 5], 3, null, 1];
        yield [[1, 3, 5], 5, null, 2];
        yield [[1, 3, 5], -1, null, -1];
        yield [[1, 3, 5], 2, null, -1];
        yield [[1, 3, 5], 4, null, -1];
        yield [[1, 3, 5], 6, null, -1];

        $desc = fn($a, $b) => $b <=> $a;
        yield [[], 0, $desc, -1];
        yield [[1], 0, $desc, -1];
        yield [[1], 1, $desc, 0];
        yield [[5, 3, 1], 1, $desc, 2];
        yield [[5, 3, 1], 3, $desc, 1];
        yield [[5, 3, 1], 5, $desc, 0];
        yield [[5, 3, 1], -1, $desc, -1];
        yield [[5, 3, 1], 2, $desc, -1];
        yield [[5, 3, 1], 4, $desc, -1];
        yield [[5, 3, 1], 6, $desc, -1];
    }

    /**
     * @template T
     *
     * @param array{T} $a
     * @param T $key
     * @param Closure|null $comparator
     * @param int $expected
     *
     * @return void
     */
    #[Test, DataProvider('indexOfTests')]
    public function indexOf(
        array $a,
        mixed $key,
        ?Closure $comparator,
        int $expected,
    ): void {
        self::assertEquals(
            $expected,
            BinarySearch::indexOf($a, $key, $comparator)
        );
    }

    /**
     * @return iterable<array{array{}|array{mixed}, mixed, Closure(mixed, mixed):int|null, int}>
     */
    public static function searchTests(): iterable
    {
        yield [[], 0, null, 0];
        yield [[1], 0, null, 0];
        yield [[1], 1, null, 0];
        yield [[1, 3, 5], 1, null, 0];
        yield [[1, 3, 5], 3, null, 1];
        yield [[1, 3, 5], 5, null, 2];
        yield [[1, 3, 5], -1, null, 0];
        yield [[1, 3, 5], 2, null, 1];
        yield [[1, 3, 5], 4, null, 2];
        yield [[1, 3, 5], 6, null, 3];

        $desc = fn($a, $b) => $b <=> $a;
        yield [[], 0, $desc, 0];
        yield [[1], 0, $desc, 1];
        yield [[1], 1, $desc, 0];
        yield [[5, 3, 1], 1, $desc, 2];
        yield [[5, 3, 1], 3, $desc, 1];
        yield [[5, 3, 1], 5, $desc, 0];
        yield [[5, 3, 1], -1, $desc, 3];
        yield [[5, 3, 1], 2, $desc, 2];
        yield [[5, 3, 1], 4, $desc, 1];
        yield [[5, 3, 1], 6, $desc, 0];
    }

    /**
     * @template T
     * @param T[] $a
     * @param T $key
     * @param Closure|null $comparator
     * @param int $expected
     * @return void
     */
    #[Test, DataProvider('searchTests')]
    public function search(
        array $a,
        mixed $key,
        ?Closure $comparator,
        int $expected,
    ): void {
        self::assertEquals(
            $expected,
            BinarySearch::search($a, $key, $comparator)
        );
    }
}