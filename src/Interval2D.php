<?php

declare(strict_types=1);

namespace Kellegous\Algs4;

final readonly class Interval2D
{
    public function __construct(
        private Interval1D $x,
        private Interval1D $y
    ) {
    }

    public static function fromXY(
        Interval1D $x,
        Interval1D $y
    ): self {
        return new self($x, $y);
    }

    public function intersects(self $that): bool
    {
        return $this->x->intersects($that->x)
            && $this->y->intersects($that->y);
    }

    public function x(): Interval1D
    {
        return $this->x;
    }

    public function y(): Interval1D
    {
        return $this->y;
    }

    public function area(): float
    {
        return $this->x->length() * $this->y->length();
    }

    public function __toString(): string
    {
        return sprintf('%s x %s', $this->x, $this->y);
    }
}