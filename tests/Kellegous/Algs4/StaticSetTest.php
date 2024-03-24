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
            new StaticSet([]),
            [[-1, -1], [0, -1], [1, -1]]
        ];

        yield 'one' => [
            new StaticSet([42]),
            [[42, 0], [0, -1]],
        ];

        yield 'many' => [
            new StaticSet([3, 1, 2]),
            [[0, -1], [1, 0], [2, 1], [3, 2], [4, -1]],
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
     * @return iterable<array{StaticSet<string>, array{string, int}[]}>
     */
    public static function rankWithStringTests(): iterable
    {
        yield 'empty' => [
            new StaticSet([]),
            [['a', -1], ['b', -1], ['c', -1]]
        ];

        yield 'one' => [
            new StaticSet(['b']),
            [['a', -1], ['b', 0], ['c', -1]],
        ];

        yield 'many' => [
            new StaticSet(['c', 'a', 'b']),
            [['a', 0], ['b', 1], ['c', 2], ['d', -1]],
        ];

        yield 'numeric strings' => [
            new StaticSet(['3', '20', '100']),
            [['a', -1], ['100', 0], ['20', 1], ['3', 2]]
        ];
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
}