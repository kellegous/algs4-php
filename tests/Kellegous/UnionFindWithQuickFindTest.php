<?php

namespace Kellegous;

use Kellegous\Testing\Goodies;
use PHPUnit\Framework\TestCase;

class UnionFindWithQuickFindTest extends TestCase
{
    use Goodies;

    public function testUnionFind()
    {
        $uf = new UnionFindWithQuickFind(4);
        $this->assertCount(4, $uf);
        for ($i = 0; $i < 4; $i++) {
            for ($j = $i + 1; $j < 4; $j++) {
                $this->assertNotSame(
                    $uf->find($i),
                    $uf->find($j),
                    "find({$i}) != find({$j})"
                );
            }
        }

        // union(0, 1)
        $uf->union(0, 1);
        $this->assertCount(3, $uf);
        $this->assertSame(
            $uf->find(0),
            $uf->find(1),
            'find(0) == find(1)'
        );
        $this->assertNotSame(
            $uf->find(0),
            $uf->find(2),
            'find(0) != find(2)'
        );
        $this->assertNotSame(
            $uf->find(0),
            $uf->find(3),
            'find(0) != find(3)'
        );
        $this->assertNotSame(
            $uf->find(1),
            $uf->find(2),
            'find(1) != find(2)'
        );
        $this->assertNotSame(
            $uf->find(1),
            $uf->find(3),
            'find(1) != find(3)'
        );
        $this->assertNotSame(
            $uf->find(2),
            $uf->find(3),
            'find(2) != find(3)'
        );

        // repeat union(0, 1)
        $uf->union(0, 1);
        $this->assertCount(3, $uf);

        // union(0, 3)
        $uf->union(0, 3);
        $this->assertCount(2, $uf);
        $this->assertSame(
            $uf->find(0),
            $uf->find(3),
            "find(0) == find(3)"
        );
        $this->assertSame(
            $uf->find(1),
            $uf->find(3),
            "find(1) == find(3)"
        );
        $this->assertNotSame(
            $uf->find(0),
            $uf->find(2),
            'find(0) != find(2)'
        );

        // union(1, 3) noop
        $this->assertCount(2, $uf);

        // union(1, 2)
        $uf->union(1, 2);
        $this->assertCount(1, $uf);
        for ($i = 1, $f = $uf->find(0); $i < 4; $i++) {
            $this->assertSame(
                $f,
                $uf->find($i),
                "find(0) == find({$i}})"
            );
        }

        $this->assertInstanceOf(
            \InvalidArgumentException::class,
            self::captureException(function () use ($uf) {
                $uf->find(-1);
            })
        );

        $this->assertInstanceOf(
            \InvalidArgumentException::class,
            self::captureException(function () use ($uf) {
                $uf->find(4);
            })
        );

        $this->assertInstanceOf(
            \InvalidArgumentException::class,
            self::captureException(function () use ($uf) {
                $uf->union(-1, 0);
            })
        );

        $this->assertInstanceOf(
            \InvalidArgumentException::class,
            self::captureException(function () use ($uf) {
                $uf->union(0, -1);
            })
        );

        $this->assertInstanceOf(
            \InvalidArgumentException::class,
            self::captureException(function () use ($uf) {
                $uf->union(4, 0);
            })
        );

        $this->assertInstanceOf(
            \InvalidArgumentException::class,
            self::captureException(function () use ($uf) {
                $uf->union(0, 4);
            })
        );
    }
}