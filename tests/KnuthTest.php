<?php

declare(strict_types=1);

namespace Kellegous\Algs4;

use Closure;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(Knuth::class)]
class KnuthTest extends TestCase
{
    /**
     * @return iterable<array{array{int}|array{}, Closure(int):int|null, Closure(array{}):void}>
     */
    public static function shuffleTests(): iterable
    {
        yield 'empty' => [[], null, fn($a) => self::assertEquals([], $a)];
        yield 'always swap with 0' => [
            [1, 2, 3],
            fn() => 0,
            fn(array $a) => self::assertEquals([3, 1, 2], $a),
        ];
        yield 'always swap with $n' => [
            [1, 2, 3],
            fn(int $n) => $n,
            fn(array $a) => self::assertEquals([1, 2, 3], $a),
        ];
        yield 'with default random_int' => [
            [1, 2, 3],
            null,
            function (array $a) {
                sort($a);
                self::assertEquals([1, 2, 3], $a);
            },
        ];
    }

    /**
     * @template T
     * @param T[] $a
     * @param Closure(int):int|null $random_int
     * @param Closure(T[]):void $validator
     * @return void
     */
    #[Test, DataProvider('shuffleTests')]
    public function shuffle(
        array $a,
        ?Closure $random_int,
        Closure $validator,
    ): void {
        $validator(Knuth::shuffle($a, $random_int));
    }

    /**
     * @template T
     * @param T[] $a
     * @param Closure(int):int|null $random_int
     * @param Closure(T[]):void $validator
     * @return void
     */
    #[Test, DataProvider('shuffleTests')]
    public function shuffleAlternate(
        array $a,
        ?Closure $random_int,
        Closure $validator,
    ): void {
        $validator(Knuth::shuffle($a, $random_int));
    }
}