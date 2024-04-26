<?php

declare(strict_types=1);

namespace Kellegous\Algs4;

/**
 *  The {@code Accumulator} class is a data type for computing the running
 *  mean, sample standard deviation, and sample variance of a stream of real
 *  numbers. It provides an example of a mutable data type and a streaming
 *  algorithm.
 *  <p>
 *  This implementation uses a one-pass algorithm that is less susceptible
 *  to floating-point roundoff error than the more straightforward
 *  implementation based on saving the sum of the squares of the numbers.
 *  This technique is due to
 *  <a href = "https://en.wikipedia.org/wiki/Algorithms_for_calculating_variance#Welford's_online_algorithm">B. P. Welford</a>.
 *  Each operation takes constant time in the worst case.
 *  The amount of memory is constant - the data values are not stored.
 *  <p>
 *  For additional documentation,
 *  see <a href="https://algs4.cs.princeton.edu/12oop">Section 1.2</a> of
 *  <i>Algorithms, 4th Edition</i> by Robert Sedgewick and Kevin Wayne.
 *
 * @author Robert Sedgewick
 * @author Kevin Wayne
 * @author Kelly Norton
 */
final class Accumulator
{
    /**
     * number of data values
     * @var int
     */
    private int $n = 0;

    /**
     * sample variance * (n - 1)
     * @var float
     */
    private float $mu = NAN;

    /**
     * sample mean
     * @var float
     */
    private float $sum = NAN;

    /**
     * Adds the specified data value to the accumulator.
     * @param float $x the data value
     */
    public function add(float $x): void
    {
        if ($this->n === 0) {
            $this->mu = 0.0;
            $this->sum = 0.0;
        }

        $this->n++;
        $n = $this->n;
        $delta = $x - $this->mu;
        $this->mu += $delta / $n;
        $this->sum += ($n - 1) / $n * $delta * $delta;
    }

    /**
     * Accumulate the values in the given iterable.
     *
     * @param iterable<float> $values
     * @return self
     */
    public static function withValues(iterable $values): self
    {
        $acc = new self();
        foreach ($values as $value) {
            $acc->add($value);
        }
        return $acc;
    }

    /**
     * Returns the mean of the data values.
     * @return float the mean of the data values
     */
    public function mean(): float
    {
        return $this->mu;
    }

    /**
     * Returns the sample variance of the data values.
     * @return float the sample variance of the data values
     */
    public function variance(): float
    {
        return ($this->n > 1)
            ? $this->sum / ($this->n - 1)
            : NAN;
    }

    /**
     * Returns the sample standard deviation of the data values.
     * @return float the sample standard deviation of the data values
     */
    public function standardDeviation(): float
    {
        return sqrt($this->variance());
    }

    /**
     * Returns the number of data values.
     * @return int the number of data values
     */
    public function count(): int
    {
        return $this->n;
    }

    /**
     * Returns a string representation of this accumulator.
     * @return string a string representation of this accumulator
     */
    public function __toString(): string
    {
        return sprintf(
            "n = %d, mean = %.5f, stddev = %.5f",
            $this->n,
            $this->mean(),
            $this->standardDeviation()
        );
    }
}