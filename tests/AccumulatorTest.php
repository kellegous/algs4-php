<?php

declare(strict_types=1);

namespace Kellegous\Algs4;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(Accumulator::class)]
class AccumulatorTest extends TestCase
{
    private static function assertFloatEquals(
        float $expected,
        float $actual,
        float $delta = 0.0001,
    ): void {
        if (is_nan($expected)) {
            self::assertNan($actual);
        } else {
            self::assertEqualsWithDelta($expected, $actual, $delta);
        }
    }

    /**
     * @return iterable<array{float[], float, float, float}>
     */
    public static function accumulationTests(): iterable
    {
        yield 'empty' => [[], NAN, NAN, NAN];

        yield 'one' => [[1.0], 1.0, NAN, NAN];

        yield 'two' => [[1.0, 2.0], 1.5, 0.7071, 0.5];

        yield 'several' => [
            [-10, -5, 0, 5, 10],
            0.0,
            7.9057,
            62.5,
        ];
    }

    /**
     * @param float[] $values
     * @param float $expectedMean
     * @param float $expectedStandardDeviation
     * @param float $expectedVariance
     * @return void
     */
    #[Test, DataProvider('accumulationTests')]
    public function accumulation(
        array $values,
        float $expectedMean,
        float $expectedStandardDeviation,
        float $expectedVariance,
    ): void {
        $acc = Accumulator::withValues($values);
        self::assertEquals(count($values), $acc->count());
        self::assertFloatEquals($expectedMean, $acc->mean());
        self::assertFloatEquals($expectedStandardDeviation, $acc->standardDeviation());
        self::assertFloatEquals($expectedVariance, $acc->variance());
    }
}