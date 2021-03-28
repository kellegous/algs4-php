<?php

namespace Kellegous;

use Kellegous\Testing\Goodies;
use PHPUnit\Framework\TestCase;

class QueueTest extends TestCase
{
    use Goodies;

    public function testQueue()
    {
        $queue = new Queue();
        $this->assertTrue($queue->isEmpty());
        $this->assertCount(0, $queue);
        $this->assertSame([], iterator_to_array($queue->iterate()));
        $this->assertInstanceOf(
            NoSuchElementException::class,
            self::captureException(function () use ($queue) {
                $queue->peek();
            })
        );
        $this->assertInstanceOf(
            NoSuchElementException::class,
            self::captureException(function () use ($queue) {
                $queue->dequeue();
            })
        );

        // enqueue 100
        $queue->enqueue(100);
        $this->assertFalse($queue->isEmpty());
        $this->assertCount(1, $queue);
        $this->assertSame([100], iterator_to_array($queue->iterate()));
        $this->assertSame(100, $queue->peek());

        // enqueue 200
        $queue->enqueue(200);
        $this->assertFalse($queue->isEmpty());
        $this->assertCount(2, $queue);
        $this->assertSame([100, 200], iterator_to_array($queue->iterate()));
        $this->assertSame(100, $queue->peek());

        // dequeue
        $this->assertSame(100, $queue->dequeue());
        $this->assertFalse($queue->isEmpty());
        $this->assertCount(1, $queue);
        $this->assertSame([200], iterator_to_array($queue->iterate()));
        $this->assertSame(200, $queue->peek());

        // enqueue 300
        $queue->enqueue(300);
        $this->assertFalse($queue->isEmpty());
        $this->assertCount(2, $queue);
        $this->assertSame([200, 300], iterator_to_array($queue->iterate()));
        $this->assertSame(200, $queue->peek());

        // dequeue
        $this->assertSame(200, $queue->dequeue());
        $this->assertFalse($queue->isEmpty());
        $this->assertCount(1, $queue);
        $this->assertSame([300], iterator_to_array($queue->iterate()));
        $this->assertSame(300, $queue->peek());

        // dequeue
        $this->assertSame(300, $queue->dequeue());
        $this->assertTrue($queue->isEmpty());
        $this->assertCount(0, $queue);
        $this->assertSame([], iterator_to_array($queue->iterate()));
        $this->assertInstanceOf(
            NoSuchElementException::class,
            self::captureException(function () use ($queue) {
                $queue->peek();
            })
        );
        $this->assertInstanceOf(
            NoSuchElementException::class,
            self::captureException(function () use ($queue) {
                $queue->dequeue();
            })
        );
    }
}