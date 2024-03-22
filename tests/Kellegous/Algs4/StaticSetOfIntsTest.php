<?php

declare(strict_types=1);

namespace Kellegous\Algs4;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(StaticSetOfInts::class)]
final class StaticSetOfIntsTest extends TestCase
{
    /**
     * @return iterable<array{StaticSetOfInts, array<int, int>}>
     */
    public static function rankTests(): iterable
    {
        yield 'empty' => [
            new StaticSetOfInts([]),
            [-1 => -1, 0 => -1, 1 => -1],
        ];

        yield 'one' => [
            new StaticSetOfInts([42]),
            [42 => 0, 0 => -1],
        ];

        yield 'many' => [
            new StaticSetOfInts([3, 1, 2]),
            [0 => -1, 1 => 0, 2 => 1, 3 => 2, 4 => -1],
        ];
    }

    /**
     * @param StaticSetOfInts $set
     * @param array<int, int> $expected
     * @return void
     */
    #[Test, DataProvider("rankTests")]
    public function rank(
        StaticSetOfInts $set,
        array $expected,
    ): void {
        $ranks = [];
        foreach ($expected as $key => $rank) {
            $ranks[$key] = $set->rank($key);
        }
        self::assertEquals($expected, $ranks);
    }

    /**
     * @return iterable<array{StaticSetOfInts, array<int, bool>}>
     */
    public static function containsTests(): iterable
    {
        yield 'emtpy' => [
            new StaticSetOfInts([]),
            [-1 => false, 0 => false, 1 => false],
        ];

        yield 'one' => [
            new StaticSetOfInts([42]),
            [42 => true, 0 => false],
        ];

        yield 'many' => [
            new StaticSetOfInts([3, 1, 2]),
            [0 => false, 1 => true, 2 => true, 3 => true, 4 => false],
        ];
    }

    /**
     * @param StaticSetOfInts $set
     * @param array<int, bool> $expected
     * @return void
     */
    #[Test, DataProvider('containsTests')]
    public function contains(
        StaticSetOfInts $set,
        array $expected,
    ): void {
        $contains = [];
        foreach ($expected as $key => $val) {
            $contains[$key] = $set->contains($key);
        }
        self::assertEquals($expected, $contains);
    }

    #[Test]
    public function duplicateKeys(): void
    {
        self::expectExceptionObject(
            new \InvalidArgumentException(
                "keys contains duplicate value: 1"
            )
        );

        new StaticSetOfInts([3, 1, 0, 2, 1]);
    }
}