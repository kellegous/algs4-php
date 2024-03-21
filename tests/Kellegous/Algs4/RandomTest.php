<?php

namespace Kellegous\Algs4;

use Closure;
use Generator;
use Kellegous\Algs4\Testing\Stats;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Random\Engine\Mt19937;

#[CoversClass(Random::class)]
class RandomTest extends TestCase
{
    private const int N = 10000;

    /**
     * @template T
     * @param Random $random
     * @param int $n
     * @param Closure(Random): T $fn
     * @return Generator<T>
     */
    private static function sample(
        Random $random,
        int $n,
        Closure $fn,
    ): Generator {
        for ($i = 0; $i < $n; $i++) {
            yield $fn($random);
        }
    }

    #[Test]
    public function gaussianDistribution(): void
    {
        $stats = Stats::from(
            self::sample(
                Random::withEngine(new Mt19937(0x420)),
                self::N,
                fn(Random $r) => $r->gaussian(),
            )
        );

        $confidence = 2.58 / sqrt(self::N);
        self::assertEqualsWithDelta(0.0, $stats->mean(), $confidence);
        self::assertEqualsWithDelta(1.0, $stats->stddev(), $confidence);

        $stats = Stats::from(
            self::sample(
                Random::withEngine(new Mt19937(0x420)),
                self::N,
                fn(Random $r) => $r->gaussian(10.0, 4.0),
            )
        );

        $confidence = 2.58 * 4.0 / sqrt(self::N);
        self::assertEqualsWithDelta(10.0, $stats->mean(), $confidence);
        self::assertEqualsWithDelta(4.0, $stats->stddev(), $confidence);
    }

    #[Test]
    public function uniformIntDistribution(): void
    {
        $stats = Stats::from(
            self::sample(
                Random::withEngine(new Mt19937(0x420)),
                self::N,
                fn(Random $r) => $r->uniformInt(-100, 100),
            )
        );

        $stddev = 200 / sqrt(12);
        $stderr = $stddev / sqrt(self::N);
        $confidence = 2.58 * $stderr; // the size of the 99% confidence interval
        self::assertEqualsWithDelta(0.0, $stats->mean(), $confidence);
        self::assertEqualsWithDelta($stddev, $stats->stddev(), $confidence);
        self::assertGreaterThanOrEqual(-100, $stats->min());
        self::assertLessThan(100, $stats->max());

        $stats = Stats::from(
            self::sample(
                Random::withEngine(new Mt19937(0x420)),
                self::N,
                fn(Random $r) => $r->uniformInt(),
            )
        );
        self::assertGreaterThanOrEqual(PHP_INT_MIN, $stats->min());
        self::assertLessThan(PHP_INT_MAX, $stats->max());
    }

    #[Test]
    public function uniformFloatDistribution(): void
    {
        $stats = Stats::from(
            self::sample(
                Random::withEngine(new Mt19937(0x420)),
                self::N,
                fn(Random $r) => $r->uniformFloat(-100.0, 100.0),
            )
        );
        $stddev = 200 / sqrt(12);
        $stderr = $stddev / sqrt(self::N);
        $confidence = 2.58 * $stderr; // the size of the 99% confidence interval
        self::assertEqualsWithDelta(0.0, $stats->mean(), $confidence);
        self::assertEqualsWithDelta($stddev, $stats->stddev(), $confidence);

        $stats = Stats::from(
            self::sample(
                Random::withEngine(new Mt19937(0x420)),
                self::N,
                fn(Random $r) => $r->uniformFloat(),
            )
        );
        $stddev = 1.0 / sqrt(12);
        $stderr = $stddev / sqrt(self::N);
        $confidence = 2.58 * $stderr; // the size of the 99% confidence interval
        self::assertEqualsWithDelta(0.5, $stats->mean(), $confidence);
        self::assertEqualsWithDelta($stddev, $stats->stddev(), $confidence);
        self::assertGreaterThanOrEqual(0, $stats->min());
        self::assertLessThan(1, $stats->max());
    }

    #[Test]
    public function bernoulliDistribution(): void
    {
        $stats = Stats::from(
            self::sample(
                Random::withEngine(new Mt19937(0x420)),
                self::N,
                fn(Random $r) => (int)$r->bernoulli(),
            )
        );

        $stddev = 2 / sqrt(12);
        $confidence = 2.58 * $stddev / sqrt(self::N);
        self::assertEqualsWithDelta(0.5, $stats->mean(), $confidence);

        $stats = Stats::from(
            self::sample(
                Random::withEngine(new Mt19937(0x420)),
                self::N,
                fn(Random $r) => (int)$r->bernoulli(1.0),
            )
        );
        self::assertEquals(1.0, $stats->mean());

        $stats = Stats::from(
            self::sample(
                Random::withEngine(new Mt19937(0x420)),
                self::N,
                fn(Random $r) => (int)$r->bernoulli(0.0),
            )
        );
        self::assertEquals(0.0, $stats->mean());
    }

    /**
     * @return iterable<array{Closure():mixed, \Exception}>
     */
    public static function invalidInputTests(): iterable
    {
        $random = Random::withEngine(new Mt19937(0x420));
        yield 'uniform int invalid range' => [
            fn() => $random->uniformInt(1, 0),
            new \InvalidArgumentException('invalid range: [1, 0)')
        ];

        yield 'uniform float invalid range' => [
            fn() => $random->uniformFloat(1.1, 0.2),
            new \InvalidArgumentException('invalid range: [1.1, 0.2)')
        ];

        yield 'bernoulli p < 0' => [
            fn() => $random->bernoulli(-0.1),
            new \InvalidArgumentException('probability p must be between 0.0 and 1.0: -0.1')
        ];

        yield 'bernoulli p > 1' => [
            fn() => $random->bernoulli(1.1),
            new \InvalidArgumentException('probability p must be between 0.0 and 1.0: 1.1')
        ];
    }

    #[Test]
    #[DataProvider('invalidInputTests')]
    public function invalidInputs(
        Closure $fn,
        \Exception $exception
    ): void {
        self::expectExceptionObject($exception);
        $fn();
    }
}