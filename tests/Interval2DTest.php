<?php

declare(strict_types=1);

namespace Kellegous\Algs4;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(Interval2D::class)]
class Interval2DTest extends TestCase
{
    /**
     * @return iterable<string, array{Interval1D, Interval1D}>
     */
    public static function creationTests(): iterable
    {
        yield 'empty' => [
            Interval1D::fromMinMax(0.0, 0.0),
            Interval1D::fromMinMax(0.0, 0.0),
        ];

        yield 'common case' => [
            Interval1D::fromMinMax(1.0, 2.0),
            Interval1D::fromMinMax(3.0, 4.0),
        ];
    }

    /**
     * @return iterable<string, array{Interval2D, Interval2D, bool}>
     */
    public static function intersectsTests(): iterable
    {
        yield 'disjoint both dimentions' => [
            Interval2D::fromXY(
                Interval1D::fromMinMax(0.0, 1.0),
                Interval1D::fromMinMax(0.0, 1.0)
            ),
            Interval2D::fromXY(
                Interval1D::fromMinMax(2.0, 3.0),
                Interval1D::fromMinMax(2.0, 3.0)
            ),
            false,
        ];

        yield 'disjoint x' => [
            Interval2D::fromXY(
                Interval1D::fromMinMax(0.0, 1.0),
                Interval1D::fromMinMax(0.0, 1.0)
            ),
            Interval2D::fromXY(
                Interval1D::fromMinMax(2.0, 3.0),
                Interval1D::fromMinMax(0.5, 1.0)
            ),
            false,
        ];

        yield 'disjoint y' => [
            Interval2D::fromXY(
                Interval1D::fromMinMax(0.0, 1.0),
                Interval1D::fromMinMax(0.0, 1.0)
            ),
            Interval2D::fromXY(
                Interval1D::fromMinMax(0.5, 1.2),
                Interval1D::fromMinMax(2.0, 3.0)
            ),
            false,
        ];

        yield 'intersecting' => [
            Interval2D::fromXY(
                Interval1D::fromMinMax(0.0, 1.0),
                Interval1D::fromMinMax(0.0, 1.0)
            ),
            Interval2D::fromXY(
                Interval1D::fromMinMax(0.5, 1.5),
                Interval1D::fromMinMax(0.5, 1.5)
            ),
            true,
        ];
    }

    /**
     * @return iterable<string, array{Interval2D, float}>
     */
    public static function areaTests(): iterable
    {
        yield 'empty' => [
            Interval2D::fromXY(
                Interval1D::fromMinMax(0.0, 0.0),
                Interval1D::fromMinMax(0.0, 0.0)
            ),
            0.0,
        ];

        yield 'common case' => [
            Interval2D::fromXY(
                Interval1D::fromMinMax(0.5, 1.0),
                Interval1D::fromMinMax(0.5, 1.0)
            ),
            0.25,
        ];
    }

    /**
     * @return iterable<string, array{Interval2D, string}>
     */
    public static function asStringTests(): iterable
    {
        yield 'empty' => [
            Interval2D::fromXY(
                Interval1D::fromMinMax(0.0, 0.0),
                Interval1D::fromMinMax(0.0, 0.0)
            ),
            '[0, 0] x [0, 0]',
        ];

        yield 'common case' => [
            Interval2D::fromXY(
                Interval1D::fromMinMax(0.5, 1.23),
                Interval1D::fromMinMax(0.5, 1.23)
            ),
            '[0.5, 1.23] x [0.5, 1.23]',
        ];
    }

    /**
     * @return iterable<string, array{Interval2D, Point2D, bool}>
     */
    public static function containsTests(): iterable
    {
        yield 'center' => [
            Interval2D::fromXY(
                Interval1D::fromMinMax(-1, 1),
                Interval1D::fromMinMax(-1, 1)
            ),
            Point2D::fromXY(0, 0),
            true
        ];

        yield '< x' => [
            Interval2D::fromXY(
                Interval1D::fromMinMax(-1, 1),
                Interval1D::fromMinMax(-1, 1)
            ),
            Point2D::fromXY(-2, 0),
            false
        ];

        yield '> x' => [
            Interval2D::fromXY(
                Interval1D::fromMinMax(-1, 1),
                Interval1D::fromMinMax(-1, 1)
            ),
            Point2D::fromXY(2, 0),
            false
        ];

        yield '< y' => [
            Interval2D::fromXY(
                Interval1D::fromMinMax(-1, 1),
                Interval1D::fromMinMax(-1, 1)
            ),
            Point2D::fromXY(0, -2),
            false
        ];

        yield '> y' => [
            Interval2D::fromXY(
                Interval1D::fromMinMax(-1, 1),
                Interval1D::fromMinMax(-1, 1)
            ),
            Point2D::fromXY(0, 2),
            false
        ];

        yield 'on border' => [
            Interval2D::fromXY(
                Interval1D::fromMinMax(-1, 1),
                Interval1D::fromMinMax(-1, 1)
            ),
            Point2D::fromXY(1, 1),
            true
        ];
    }

    /**
     * @param Interval1D $x
     * @param Interval1D $y
     * @return void
     */
    #[Test, DataProvider("creationTests")]
    public function creation(
        Interval1D $x,
        Interval1D $y,
    ): void {
        $interval = Interval2D::fromXY($x, $y);
        self::assertSame($x, $interval->x());
        self::assertSame($y, $interval->y());

        $interval = new Interval2D($x, $y);
        self::assertSame($x, $interval->x());
        self::assertSame($y, $interval->y());
    }

    /**
     * @param Interval2D $a
     * @param Interval2D $b
     * @param bool $expected
     * @return void
     */
    #[Test, DataProvider("intersectsTests")]
    public function intersects(
        Interval2D $a,
        Interval2D $b,
        bool $expected,
    ): void {
        self::assertEquals($expected, $a->intersects($b));
    }

    /**
     * @param Interval2D $interval
     * @param float $expected
     * @return void
     */
    #[Test, DataProvider("areaTests")]
    public function area(
        Interval2D $interval,
        float $expected
    ): void {
        self::assertEquals($expected, $interval->area());
    }

    /**
     * @param Interval2D $interval
     * @param string $expected
     * @return void
     */
    #[Test, DataProvider("asStringTests")]
    public function asString(
        Interval2D $interval,
        string $expected
    ): void {
        self::assertEquals($expected, (string)$interval);
    }

    #[Test, DataProvider('containsTests')]
    public function contains(
        Interval2D $interval,
        Point2D $point,
        bool $expected
    ): void {
        self::assertEquals($expected, $interval->contains($point));
    }
}