<?php

namespace Kellegous\Algs4;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class BagTest extends TestCase
{
    /**
     * @template T
     * @param Bag<T> $bag
     * @param array<T> $expected
     *
     * @return void
     */
    private static function assertContainsValues(Bag $bag, array $expected): void
    {
        sort($expected);
        $values = iterator_to_array($bag);
        sort($values);
        self::assertEquals($expected, $values);
    }

    #[Test]
    public function empty(): void
    {
        $bag = new Bag();
        self::assertTrue($bag->isEmpty());
        self::assertCount(0, $bag);
    }

    #[Test]
    public function addInt(): void
    {
        /** @var Bag<int> $bag */
        $bag = new Bag();
        $bag->add(100);
        self::assertFalse($bag->isEmpty());
        self::assertCount(1, $bag);
        self::assertContainsValues($bag, [100]);

        $bag->add(200);
        self::assertFalse($bag->isEmpty());
        self::assertCount(2, $bag);
        self::assertContainsValues($bag, [100, 200]);
    }

    #[Test]
    public function addString(): void
    {
        /** @var Bag<string> $bag */
        $bag = new Bag();
        $bag->add('hello');
        self::assertFalse($bag->isEmpty());
        self::assertCount(1, $bag);
        self::assertContainsValues($bag, ['hello']);

        $bag->add('world');
        self::assertFalse($bag->isEmpty());
        self::assertCount(2, $bag);
        self::assertContainsValues($bag, ['hello', 'world']);
    }
}