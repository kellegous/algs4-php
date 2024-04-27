<?php

namespace Kellegous\Algs4;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(StaticSet::class)]
final class StaticSetTest extends TestCase
{
    /**
     * @return iterable<array{StaticSet<int>, array{int, int}[]}>
     */
    public static function rankWithIntTests(): iterable
    {
        yield 'empty' => [
            new StaticSet([], fn(int $a, int $b) => $a <=> $b),
            [[-1, -1], [0, -1], [1, -1]]
        ];

        yield 'one' => [
            new StaticSet([42], fn(int $a, int $b) => $a <=> $b),
            [[42, 0], [0, -1]],
        ];

        yield 'many' => [
            new StaticSet([3, 1, 2], fn(int $a, int $b) => $a <=> $b),
            [[0, -1], [1, 0], [2, 1], [3, 2], [4, -1]],
        ];
    }

    /**
     * @return iterable<array{StaticSet<string>, array{string, int}[]}>
     */
    public static function rankWithStringTests(): iterable
    {
        yield 'empty' => [
            new StaticSet([], fn(string $a, string $b) => strcmp($a, $b)),
            [['a', -1], ['b', -1], ['c', -1]]
        ];

        yield 'one' => [
            new StaticSet(['b'], fn(string $a, string $b) => strcmp($a, $b)),
            [['a', -1], ['b', 0], ['c', -1]],
        ];

        yield 'many' => [
            new StaticSet(['c', 'a', 'b'], fn(string $a, string $b) => strcmp($a, $b)),
            [['a', 0], ['b', 1], ['c', 2], ['d', -1]],
        ];

        yield 'numeric strings' => [
            new StaticSet(['3', '20', '100'], fn(string $a, string $b) => strcmp($a, $b)),
            [['a', -1], ['100', 0], ['20', 1], ['3', 2]]
        ];
    }

    /**
     * @return iterable<string, array{StaticSet<array{val:int}>, array<array{array{val:int}, int}>}>>
     */
    public static function rankWithCustomTests(): iterable
    {
        yield 'array values' => [
            new StaticSet(
                [
                    ['val' => 1],
                    ['val' => 5],
                    ['val' => 1],
                ],
                fn(array $a, array $b) => $a['val'] <=> $b['val']
            ),
            [
                [['val' => 1], 0],
                [['val' => 2], -1],
                [['val' => 5], 1],
                [['val' => 6], -1],
            ],
        ];
    }

    /**
     * @param StaticSet<int> $set
     * @param array{int, int}[] $expected
     * @return void
     */
    #[Test, DataProvider("rankWithIntTests")]
    public function rankWithInt(
        StaticSet $set,
        array $expected,
    ): void {
        foreach ($expected as [$key, $rank]) {
            self::assertEquals($rank, $set->rank($key));
        }
    }

    /**
     * @param StaticSet<string> $set
     * @param array{string, int}[] $expected
     * @return void
     */
    #[Test, DataProvider("rankWithStringTests")]
    public function rankWithString(
        StaticSet $set,
        array $expected,
    ): void {
        foreach ($expected as [$key, $rank]) {
            self::assertEquals($rank, $set->rank($key));
        }
    }

    /**
     * @param StaticSet<array{val:int}> $set
     * @param array<array{array{val:int}, int}> $expected
     * @return void
     */
    #[Test, DataProvider("rankWithCustomTests")]
    public function rankWithCustom(
        StaticSet $set,
        array $expected,
    ): void {
        foreach ($expected as [$key, $rank]) {
            self::assertEquals($rank, $set->rank($key));
        }
    }
}