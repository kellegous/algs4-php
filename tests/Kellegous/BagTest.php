<?php

namespace Kellegous;

use PHPUnit\Framework\TestCase;

class BagTest extends TestCase
{
    public function testEmpty() {
        $bag = new Bag();
        $this->assertTrue($bag->isEmpty());
        $this->assertEquals(0, $bag->size());
        $this->assertEquals(
            [],
            iterator_to_array($bag->iterate())
        );
    }

    public function testAdd()
    {
        $bag = new Bag();
        $values = [0, 0, 1, 2, 3, 10, 20];
        foreach ($values as $i => $value) {
            $bag->add($value);
            $this->assertEquals(
                $i + 1,
                $bag->size()
            );
            $this->assertFalse($bag->isEmpty());
        }

        $contents = iterator_to_array(
            $bag->iterate()
        );
        sort($contents);
        $this->assertEquals(
            $values,
            $contents
        );
    }
}