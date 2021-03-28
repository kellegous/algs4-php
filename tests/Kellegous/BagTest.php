<?php

namespace Kellegous;

use PHPUnit\Framework\TestCase;

class BagTest extends TestCase
{
    private function assertContainsAllItems(
        array $expected,
        Bag $bag
    ) {
        $got = iterator_to_array($bag->iterate());
        sort($expected);
        sort($got);
        $this->assertSame($expected, $got);
    }

    public function testBag()
    {
        $bag = new Bag();
        $this->assertTrue($bag->isEmpty());
        $this->assertCount(0, $bag);
        $this->assertContainsAllItems([], $bag);

        // add 100
        $bag->add(100);
        $this->assertFalse($bag->isEmpty());
        $this->assertCount(1, $bag);
        $this->assertContainsAllItems([100], $bag);

        // add 300
        $bag->add(300);
        $this->assertFalse($bag->isEmpty());
        $this->assertCount(2, $bag);
        $this->assertContainsAllItems([100, 300], $bag);

        $bag->add(200);
        $this->assertFalse($bag->isEmpty());
        $this->assertCount(3, $bag);
        $this->assertContainsAllItems([100, 200, 300], $bag);
    }
}