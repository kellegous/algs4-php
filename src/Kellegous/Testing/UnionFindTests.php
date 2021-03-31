<?php

namespace Kellegous\Testing;

use Kellegous\UnionFind;

trait UnionFindTests
{
    use Goodies;

    public abstract static function assertCount(
        int $expected,
        \Countable $countable,
        string $message = ''
    );

    public abstract static function assertNotSame(
        $expected,
        $actual,
        string $message = ''
    );

    public abstract static function assertSame(
        $expected,
        $actual,
        string $message = ''
    );

    public abstract static function assertInstanceOf(
        string $expected,
        $actual,
        string $message = ''
    );

    public abstract static function create(
        int $n
    ): UnionFind;

    public function testUnionFind()
    {
        $uf = static::create(4);
        static::assertCount(4, $uf);
        for ($i = 0; $i < 4; $i++) {
            for ($j = $i + 1; $j < 4; $j++) {
                static::assertNotSame(
                    $uf->find($i),
                    $uf->find($j),
                    "find({$i}) != find({$j})"
                );
            }
        }

        // union(0, 1)
        $uf->union(0, 1);
        static::assertCount(3, $uf);
        static::assertSame(
            $uf->find(0),
            $uf->find(1),
            'find(0) == find(1)'
        );
        static::assertNotSame(
            $uf->find(0),
            $uf->find(2),
            'find(0) != find(2)'
        );
        static::assertNotSame(
            $uf->find(0),
            $uf->find(3),
            'find(0) != find(3)'
        );
        static::assertNotSame(
            $uf->find(1),
            $uf->find(2),
            'find(1) != find(2)'
        );
        static::assertNotSame(
            $uf->find(1),
            $uf->find(3),
            'find(1) != find(3)'
        );
        static::assertNotSame(
            $uf->find(2),
            $uf->find(3),
            'find(2) != find(3)'
        );

        // repeat union(0, 1)
        $uf->union(0, 1);
        static::assertCount(3, $uf);

        // union(0, 3)
        $uf->union(0, 3);
        static::assertCount(2, $uf);
        static::assertSame(
            $uf->find(0),
            $uf->find(3),
            "find(0) == find(3)"
        );
        static::assertSame(
            $uf->find(1),
            $uf->find(3),
            "find(1) == find(3)"
        );
        static::assertNotSame(
            $uf->find(0),
            $uf->find(2),
            'find(0) != find(2)'
        );

        // union(1, 3) noop
        $uf->union(1, 3);
        static::assertCount(2, $uf);

        // union(1, 2)
        $uf->union(1, 2);
        static::assertCount(1, $uf);
        for ($i = 1, $f = $uf->find(0); $i < 4; $i++) {
            static::assertSame(
                $f,
                $uf->find($i),
                "find(0) == find({$i}})"
            );
        }

        static::assertInstanceOf(
            \InvalidArgumentException::class,
            self::captureException(function () use ($uf) {
                $uf->find(-1);
            })
        );

        static::assertInstanceOf(
            \InvalidArgumentException::class,
            self::captureException(function () use ($uf) {
                $uf->find(4);
            })
        );

        static::assertInstanceOf(
            \InvalidArgumentException::class,
            self::captureException(function () use ($uf) {
                $uf->union(-1, 0);
            })
        );

        static::assertInstanceOf(
            \InvalidArgumentException::class,
            self::captureException(function () use ($uf) {
                $uf->union(0, -1);
            })
        );

        static::assertInstanceOf(
            \InvalidArgumentException::class,
            self::captureException(function () use ($uf) {
                $uf->union(4, 0);
            })
        );

        static::assertInstanceOf(
            \InvalidArgumentException::class,
            self::captureException(function () use ($uf) {
                $uf->union(0, 4);
            })
        );
    }
}