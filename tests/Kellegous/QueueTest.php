<?php

namespace Kellegous;

use Kellegous\Testing\Goodies;
use PHPUnit\Framework\TestCase;

class QueueTest extends TestCase
{
    use Goodies;

    public function testEmpty()
    {
        $queue = new Queue();
        $this->assertTrue($queue->isEmpty());
        $this->assertEquals(0, $queue->size());
        $this->assertEquals(
            [],
            iterator_to_array($queue->iterate())
        );
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

    public function testEnqueue()
    {
        $queue = new Queue();
        $values = [0, 0, 1, 2, 3, 4, 10, 20];
        foreach ($values as $i => $value) {
            $queue->enqueue($value);
            $this->assertFalse($queue->isEmpty());
            $this->assertEquals($i + 1, $queue->size());
        }
        $this->assertEquals(
            $values,
            iterator_to_array($queue->iterate())
        );
    }

    public function testDequeue()
    {
        $queue = new Queue();
        $values = [0, 0, 1, 2, 3, 4, 10, 20];
        foreach ($values as $value) {
            $queue->enqueue($value);
        }
        foreach ($values as $i => $value) {
            $this->assertFalse($queue->isEmpty());
            $this->assertEquals(count($values) - $i, $queue->size());
            $this->assertEquals(
                $value,
                $queue->peek()
            );
            $this->assertEquals(
                $value,
                $queue->dequeue()
            );
        }
        $this->assertTrue($queue->isEmpty());
        $this->assertEquals(0, $queue->size());
        $this->assertEquals([], iterator_to_array($queue->iterate()));
    }
}