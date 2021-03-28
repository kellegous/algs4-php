<?php

namespace Kellegous;

use Kellegous\Testing\Goodies;
use PHPUnit\Framework\TestCase;

class StackTest extends TestCase
{
    use Goodies;

    public function testStack()
    {
        $stack = new Stack();
        $this->assertTrue($stack->isEmpty());
        $this->assertCount(0, $stack);
        $this->assertSame(
            [],
            iterator_to_array($stack->iterate())
        );
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

        // push 100
        $stack->push(100);
        $this->assertFalse($stack->isEmpty());
        $this->assertCount(1, $stack);
        $this->assertSame([100], iterator_to_array($stack->iterate()));
        $this->assertSame(100, $stack->peek());

        // push 200
        $stack->push(200);
        $this->assertFalse($stack->isEmpty());
        $this->assertCount(2, $stack);
        $this->assertSame([200, 100], iterator_to_array($stack->iterate()));
        $this->assertSame(200, $stack->peek());

        // pop
        $this->assertSame(200, $stack->pop());
        $this->assertFalse($stack->isEmpty());
        $this->assertCount(1, $stack);
        $this->assertSame([100], iterator_to_array($stack->iterate()));
        $this->assertSame(100, $stack->peek());

        // push 300
        $stack->push(300);
        $this->assertFalse($stack->isEmpty());
        $this->assertCount(2, $stack);
        $this->assertSame([300, 100], iterator_to_array($stack->iterate()));
        $this->assertSame(300, $stack->peek());

        // pop
        $this->assertSame(300, $stack->pop());
        $this->assertFalse($stack->isEmpty());
        $this->assertCount(1, $stack);
        $this->assertSame([100], iterator_to_array($stack->iterate()));
        $this->assertSame(100, $stack->peek());

        // pop
        $this->assertSame(100, $stack->pop());
        $this->assertTrue($stack->isEmpty());
        $this->assertCount(0, $stack);
        $this->assertSame(
            [],
            iterator_to_array($stack->iterate())
        );
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