<?php

declare(strict_types=1);

namespace Kellegous\Algs4;

use Exception;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(Interval1D::class)]
class Interval1DTest extends TestCase
{
    /**
     * @return iterable<array{float, float, ?Exception}>
     */
    public static function fromMinMaxTests(): iterable
    {
        yield 'empty' => [
            0.0,
            0.0,
            null
        ];

        yield 'common case' => [
            1.0,
            2.0,
            null
        ];

        yield 'min > max' => [
            1.0,
            0.0,
            new InvalidArgumentException(
                'illegal interval: min and max must be finite numbers where min <= max'
            )
        ];

        yield 'with NaN' => [
            NAN,
            1.0,
            new InvalidArgumentException(
                'illegal interval: min and max must be finite numbers where min <= max'
            )
        ];

        yield 'with INF' => [
            1.0,
            INF,
            new InvalidArgumentException(
                'illegal interval: min and max must be finite numbers where min <= max'
            )
        ];
    }

    /**
     * @return iterable<string, array{Interval1D, Interval1D, bool}>
     */
    public static function equalsTests(): iterable
    {
        yield 'a == b' => [
            Interval1D::fromMinMax(1.0, 2.0),
            Interval1D::fromMinMax(1.0, 2.0),
            true
        ];

        yield 'a != b' => [
            Interval1D::fromMinMax(1.0, 2.0),
            Interval1D::fromMinMax(1.0, 3.0),
            false
        ];
    }

    #[Test, DataProvider("fromMinMaxTests")]
    public function fromMinMax(
        float $min,
        float $max,
        ?Exception $expected
    ): void {
        if ($expected !== null) {
            self::expectExceptionObject($expected);
        }
        $interval = Interval1D::fromMinMax($min, $max);
        self::assertEquals($min, $interval->min());
        self::assertEquals($max, $interval->max());
    }

    /**
     * @return iterable<string, array{Interval1D, Interval1D, bool}>
     */
    public static function intersectsTests(): iterable
    {
        yield 'disjoint' => [
            Interval1D::fromMinMax(0.0, 10.0),
            Interval1D::fromMinMax(20.0, 30.0),
            false
        ];

        yield 'a intersects lower b' => [
            Interval1D::fromMinMax(0.0, 10.0),
            Interval1D::fromMinMax(5.0, 15.0),
            true
        ];

        yield 'a intersects upper b' => [
            Interval1D::fromMinMax(0.0, 10.0),
            Interval1D::fromMinMax(-5.0, 5.0),
            true
        ];

        yield 'a subset of b' => [
            Interval1D::fromMinMax(0.0, 10.0),
            Interval1D::fromMinMax(5.0, 8.0),
            true
        ];

        yield 'a and b contiguous' => [
            Interval1D::fromMinMax(0.0, 1.0),
            Interval1D::fromMinMax(1.0, 2.0),
            true
        ];
    }

    /**
     * @return iterable<string, array{Interval1D, float, bool}>
     */
    public static function containsTests(): iterable
    {
        yield 'centered' => [
            Interval1D::fromMinMax(0, 10.0),
            5.0,
            true
        ];

        yield 'min' => [
            Interval1D::fromMinMax(0, 10.0),
            0.0,
            true
        ];

        yield 'max' => [
            Interval1D::fromMinMax(0, 10.0),
            10.0,
            true
        ];

        yield 'below' => [
            Interval1D::fromMinMax(0, 10.0),
            -1.0,
            false
        ];

        yield 'above' => [
            Interval1D::fromMinMax(0, 10.0),
            11.0,
            false
        ];
    }

    /**
     * @return iterable<string, array{Interval1D, string}>
     */
    public static function asStringTests(): iterable
    {
        yield 'whole numbers' => [
            Interval1D::fromMinMax(0.0, 10.0),
            '[0, 10]'
        ];

        yield 'fractional numbers' => [
            Interval1D::fromMinMax(0.5, 10.5),
            '[0.5, 10.5]'
        ];
    }

    #[Test, DataProvider("equalsTests")]
    public function equals(
        Interval1D $a,
        Interval1D $b,
        bool $expected
    ): void {
        self::assertEquals($expected, Interval1D::equals($a, $b));
    }

    #[Test, DataProvider("intersectsTests")]
    public function intersects(
        Interval1D $a,
        Interval1D $b,
        bool $expected
    ): void {
        self::assertEquals($expected, $a->intersects($b));
    }

    #[Test, DataProvider("containsTests")]
    public function contains(
        Interval1D $interval1D,
        float $x,
        bool $expected
    ): void {
        self::assertEquals($expected, $interval1D->contains($x));
    }

    #[Test]
    public function length(): void
    {
        self::assertEquals(10.0, Interval1D::fromMinMax(0.0, 10.0)->length());
    }

    #[Test, DataProvider("asStringTests")]
    public function asString(
        Interval1D $interval,
        string $expected
    ): void {
        self::assertEquals($expected, (string)$interval);
    }
}