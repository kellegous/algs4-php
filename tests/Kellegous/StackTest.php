<?php

namespace Kellegous;

use Kellegous\Testing\Goodies;
use PHPUnit\Framework\TestCase;

class StackTest extends TestCase
{
    use Goodies;

    public function testEmpty()
    {
        $stack = new Stack();
        $this->assertTrue($stack->isEmpty());
        $this->assertCount(0, $stack);
        $this->assertEquals([], iterator_to_array($stack->iterate()));
        $this->assertInstanceOf(
            NoSuchElementException::class,
            self::captureException(function () use ($stack) {
                $stack->peek();
            })
        );
        $this->assertInstanceOf(
            NoSuchElementException::class,
            self::captureException(function () use ($stack) {
                $stack->pop();
            })
        );
    }
}