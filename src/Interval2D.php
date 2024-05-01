<?php

declare(strict_types=1);

namespace Kellegous\Algs4;

/**
 *  The {@code Interval2D} class represents a closed two-dimensional interval,
 *  which represents all points (x, y) with both {@code xmin <= x <= xmax} and
 *  {@code ymin <= y <= ymax}.
 *  Two-dimensional intervals are immutable: their values cannot be changed
 *  after they are created.
 *  The class {@code Interval2D} includes methods for checking whether
 *  a two-dimensional interval contains a point and determining whether
 *  two two-dimensional intervals intersect.
 *  <p>
 *  For additional documentation,
 *  see <a href="https://algs4.cs.princeton.edu/12oop">Section 1.2</a> of
 *  <i>Algorithms, 4th Edition</i> by Robert Sedgewick and Kevin Wayne.
 *
 * @author Robert Sedgewick
 * @author Kevin Wayne
 * @author Kelly Norton
 */
final readonly class Interval2D
{
    /**
     * Initializes a two-dimensional interval.
     * @param Interval1D $x
     * @param Interval1D $y
     */
    public function __construct(
        private Interval1D $x,
        private Interval1D $y
    ) {
    }

    /**
     * Initializes a two-dimensional interval.
     * @param Interval1D $x
     * @param Interval1D $y
     * @return self
     */
    public static function fromXY(
        Interval1D $x,
        Interval1D $y
    ): self {
        return new self($x, $y);
    }

    /**
     * Does this two-dimensional interval intersect that two-dimensional interval?
     * @param Interval2D $that
     * @return bool
     */
    public function intersects(self $that): bool
    {
        return $this->x->intersects($that->x)
            && $this->y->intersects($that->y);
    }

    /**
     * Returns the area of this two-dimensional interval.
     * @return float
     */
    public function area(): float
    {
        return $this->x->length() * $this->y->length();
    }

    /**
     * Returns a string representation of this two-dimensional interval.
     * @return string
     */
    public function __toString(): string
    {
        return sprintf('%s x %s', $this->x, $this->y);
    }

    /**
     * Does this two-dimensional interval contain the point p?
     * @param Point2D $point
     * @return bool
     */
    public function contains(Point2D $point): bool
    {
        return $this->x->contains($point->x())
            && $this->y->contains($point->y());
    }

    public function x(): Interval1D
    {
        return $this->x;
    }

    public function y(): Interval1D
    {
        return $this->y;
    }
}