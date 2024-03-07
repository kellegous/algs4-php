<?php

namespace Kellegous\Algs4\Testing;

class Stats
{
    /**
     * @param float $min
     * @param float $max
     * @param float $mean
     * @param float $stddev
     */
    public function __construct(
        private float $min,
        private float $max,
        private float $mean,
        private float $stddev,
    ) {
    }

    public function min(): float
    {
        return $this->min;
    }

    public function max(): float
    {
        return $this->max;
    }

    public function mean(): float
    {
        return $this->mean;
    }

    public function stddev(): float
    {
        return $this->stddev;
    }

    /**
     * @param \Iterator<float|int> $samples
     * @return self
     */
    public static function from(\Iterator $samples): self
    {
        $n = 0;
        $sum = 0.0;
        $sum_squared = 0.0;
        $min = PHP_INT_MAX;
        $max = PHP_INT_MIN;
        foreach ($samples as $x) {
            $n++;
            $sum += $x;
            $sum_squared += $x * $x;
            $min = min($min, $x);
            $max = max($max, $x);
        }
        $mean = $sum / $n;
        return new self(
            $min,
            $max,
            $mean,
            sqrt(($sum_squared - $sum * $sum / $n) / ($n - 1))
        );
    }
}