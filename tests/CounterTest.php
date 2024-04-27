<?php

declare(strict_types=1);

namespace Kellegous\Algs4;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(Counter::class)]
class CounterTest extends TestCase
{

    #[Test]
    public function empty(): void
    {
        $counter = new Counter('empty');
        self::assertEquals(0, $counter->tally());
        self::assertEquals('empty', $counter->name());
        self::assertEquals('0 empty', (string)$counter);
    }

    #[Test]
    public function increment(): void
    {
        $counter = new Counter('a');

        $counter->increment();
        self::assertEquals(1, $counter->tally());
        self::assertEquals('1 a', (string)$counter);

        $counter->increment();
        self::assertEquals(2, $counter->tally());
        self::assertEquals('2 a', (string)$counter);
    }

    public function compare(): void
    {
        $a = new Counter('a');
        $b = new Counter('b');

        self::assertEquals(0, Counter::compare($a, $b));

        $a->increment();
        self::assertEquals(-1, Counter::compare($a, $b));

        $b->increment();
        self::assertEquals(0, Counter::compare($a, $b));

        $b->increment();
        self::assertEquals(1, Counter::compare($a, $b));

        $b->increment();
        self::assertEquals(1, Counter::compare($a, $b));
    }
}