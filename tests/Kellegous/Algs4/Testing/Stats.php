<?php

namespace Kellegous\Algs4\Testing;

use Kellegous\Algs4\Accumulator;

class Stats
{
    /**
     * @param float $min
     * @param float $max
     * @param Accumulator $accumulator
     */
    public function __construct(
        private float $min,
        private float $max,
        private Accumulator $accumulator,
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
        return $this->accumulator->mean();
    }

    public function stddev(): float
    {
        return $this->accumulator->standardDeviation();
    }

    /**
     * @param \Iterator<float|int> $samples
     * @return self
     */
    public static function from(\Iterator $samples): self
    {
        $min = PHP_INT_MAX;
        $max = PHP_INT_MIN;
        $accumulator = new Accumulator();
        foreach ($samples as $x) {
            $min = min($min, $x);
            $max = max($max, $x);
            $accumulator->add($x);
        }
        return new self($min, $max, $accumulator);
    }
}