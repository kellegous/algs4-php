<?php

declare(strict_types=1);

namespace Kellegous\Algs4;

use InvalidArgumentException;

/**
 *  The {@code Interval1D} class represents a one-dimensional interval.
 *  The interval is <em>closed</em>—it contains both endpoints.
 *  Intervals are immutable: their values cannot be changed after they are created.
 *  The class {@code Interval1D} includes methods for checking whether
 *  an interval contains a point and determining whether two intervals intersect.
 *  <p>
 *  For additional documentation,
 *  see <a href="https://algs4.cs.princeton.edu/12oop">Section 1.2</a> of
 *  <i>Algorithms, 4th Edition</i> by Robert Sedgewick and Kevin Wayne.
 *
 * @author Robert Sedgewick
 * @author Kevin Wayne
 * @author Kelly Norton
 */
final readonly class Interval1D
{
    /**
     * Initializes a closed interval [min, max].
     *
     * @param float $min
     * @param float $max
     */
    private function __construct(
        private float $min,
        private float $max
    ) {
    }

    /**
     * Create a closed interval [min, max].
     *
     * @param float $min
     * @param float $max
     * @return self
     */
    public static function fromMinMax(float $min, float $max): self
    {
        if (is_nan($min) || is_nan($max) || is_infinite($min) || is_infinite($max) || $min > $max) {
            throw new InvalidArgumentException(
                'illegal interval: min and max must be finite numbers where min <= max'
            );
        }

        return new self($min, $max);
    }

    /**
     * @param Interval1D $a
     * @param Interval1D $b
     * @return bool
     */
    public static function equals(self $a, self $b): bool
    {
        return $a->min === $b->min && $a->max === $b->max;
    }

    /**
     * Returns the min endpoint of this interval.
     *
     * @return float
     */
    public function min(): float
    {
        return $this->min;
    }

    /**
     * Returns the max endpoint of this interval.
     *
     * @return float
     */
    public function max(): float
    {
        return $this->max;
    }

    /**
     * Returns true if this interval intersects the specified interval.
     *
     * @param Interval1D $that
     * @return bool
     */
    public function intersects(Interval1D $that): bool
    {
        return $this->max >= $that->min && $that->max >= $this->min;
    }

    /**
     * Returns true if this interval contains the specified value.
     *
     * @param float $x
     * @return bool
     */
    public function contains(float $x): bool
    {
        return $this->min <= $x && $x <= $this->max;
    }

    /**
     * Returns the length of this interval.
     *
     * @return float
     */
    public function length(): float
    {
        return $this->max - $this->min;
    }

    /**
     * Returns a string representation of this interval.
     *
     * @return string
     */
    public function __toString(): string
    {
        return sprintf('[%.5g, %.5g]', $this->min, $this->max);
    }
}