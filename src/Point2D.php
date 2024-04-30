<?php

namespace Kellegous\Algs4;

final readonly class Point2D
{
    private function __construct(
        private float $x,
        private float $y
    ) {
    }

    public static function fromXY(float $x, float $y): self
    {
        return new self($x, $y);
    }

    public function equals(self $that): bool
    {
        return $this->x === $that->x && $this->y === $that->y;
    }

    public function x(): float
    {
        return $this->x;
    }

    public function y(): float
    {
        return $this->y;
    }

    public function r(): float
    {
        $x = $this->x;
        $y = $this->y;
        return sqrt($x * $x + $y * $y);
    }

    public function theta(): float
    {
        return atan2($this->y, $this->x);
    }

    public function angleTo(self $that): float
    {
        $dx = $that->x - $this->x;
        $dy = $that->y - $this->y;
        return atan2($dy, $dx);
    }

    public function distanceTo(self $that): float
    {
        $dx = $that->x - $this->x;
        $dy = $that->y - $this->y;
        return sqrt($dx * $dx + $dy * $dy);
    }
}