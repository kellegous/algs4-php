<?php

namespace Kellegous;

use PHPUnit\Framework\TestCase;

class QueueTest extends TestCase
{
    public function testEmpty()
    {
        $queue = new Queue();
        $this->assertTrue($queue->isEmpty());
        $this->assertEquals(0, $queue->size());
        $this->assertEquals(
            [],
            iterator_to_array($queue->iterate())
        );
    }
}