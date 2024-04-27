<?php

declare(strict_types=1);

namespace Kellegous\Algs4;

use Random\Engine;
use Random\Randomizer;

/**
 * The {@code Random} class provides methods for generating
 * random number from various discrete and continuous distributions,
 * including uniform, Bernoulli, geometric, Gaussian, exponential, Pareto,
 * Poisson, and Cauchy. It also provides method for shuffling an
 * array or subarray and generating random permutations.
 *
 * <p><b>Conventions.</b>
 * By convention, all intervals are half open. For example,
 * <code>uniformDouble(-1.0, 1.0)</code> returns a random number between
 * <code>-1.0</code> (inclusive) and <code>1.0</code> (exclusive).
 * Similarly, <code>shuffle(a, lo, hi)</code> shuffles the <code>hi - lo</code>
 * elements in the array <code>a[]</code>, starting at index <code>lo</code>
 * (inclusive) and ending at index <code>hi</code> (exclusive).
 *
 * <p><b>Performance.</b>
 * The methods all take constant expected time, except those that involve arrays.
 * The <em>shuffle</em> method takes time linear in the subarray to be shuffled;
 * the <em>discrete</em> methods take time linear in the length of the argument
 * array.
 *
 * <p><b>Additional information.</b>
 * For additional documentation,
 * see <a href="https://introcs.cs.princeton.edu/22library">Section 2.2</a> of
 * <i>Computer Science: An Interdisciplinary Approach</i>
 * by Robert Sedgewick and Kevin Wayne.
 *
 * @author Robert Sedgewick
 * @author Kevin Wayne
 * @author Kelly Norton
 */
final class Random
{
    /**
     * The guassian method uses the polar form of the Box-Muller transform
     * which produces two independent gaussian values per call. This caches
     * the unused value for the next call.
     * @var float|null
     */
    private ?float $next_gaussian = null;

    /**
     * @param Randomizer $randomizer
     */
    public function __construct(
        private readonly Randomizer $randomizer
    ) {
    }

    /**
     * Create an instance from a Random\Engine.
     *
     * @param Engine $engine
     * @return self
     */
    public static function withEngine(Engine $engine): self
    {
        return new self(
            new Randomizer($engine)
        );
    }

    /**
     * Returns a random real number uniformly in [a, b).
     *
     * @param float $a the left endpoint
     * @param float $b the right endpoint
     * @return float
     * @throws \InvalidArgumentException unless {@code a < b}
     */
    public function uniformFloat(
        float $a = 0.0,
        float $b = 1.0,
    ): float {
        if ($a >= $b) {
            throw new \InvalidArgumentException(
                "invalid range: [$a, $b)"
            );
        }
        return $a + $this->randomizer->nextFloat() * ($b - $a);
    }

    /**
     * Returns a random integer uniformly in [a, b).
     *
     * @param int $a the left endpoint
     * @param int $b the right endpoint
     * @return int a random integer uniformly in [a, b)
     * @throws \InvalidArgumentException if {@code b <= a}
     */
    public function uniformInt(
        int $a = PHP_INT_MIN,
        int $b = PHP_INT_MAX - 1,
    ): int {
        if ($b <= $a) {
            throw new \InvalidArgumentException(
                "invalid range: [$a, $b)"
            );
        }

        return $this->randomizer->getInt($a, $b - 1);
    }

    /**
     * Returns a random boolean from a Bernoulli distribution with success
     * probability <em>p</em>.
     *
     * @param float $p the probability of returning {@code true}
     * @return bool {@code true} with probability {@code p} and
     *         {@code false} with probability {@code 1 - p}
     * @throws \InvalidArgumentException if p < 0 or if p > 0
     */
    public function bernoulli(float $p = 0.5): bool
    {
        if ($p < 0.0 || $p > 1.0) {
            throw new \InvalidArgumentException(
                "probability p must be between 0.0 and 1.0: {$p}"
            );
        }
        return $this->randomizer->nextFloat() < $p;
    }

    /**
     * Returns a random real number from a Gaussian distribution with mean &mu;
     * and standard deviation &sigma;.
     *
     * @param float $mu the mean
     * @param float $sigma the standard deviation
     * @return float a real number distributed according to the Gaussian distribution
     *         with mean {@code $mu} and standard deviation {@code $sigma}
     */
    public function gaussian(float $mu = 0.0, float $sigma = 1.0): float
    {
        $x = $this->next_gaussian;
        if ($x !== null) {
            $this->next_gaussian = null;
            return $mu + $sigma * $x;
        }

        $r = 0.0;
        do {
            $x = $this->uniformFloat(-1.0, 1.0);
            $y = $this->uniformFloat(-1.0, 1.0);
            $r = $x * $x + $y * $y;
        } while ($r >= 1 || $r == 0.0);

        $x = $x * sqrt(-2.0 * log($r) / $r);
        $y = $y * sqrt(-2.0 * log($r) / $r);
        $this->next_gaussian = $y;
        return $mu + $sigma * $x;
    }

    /**
     * Returns a random integer from a geometric distribution with success
     * probability <em>p</em>.
     * The integer represents the number of independent trials
     * before the first success.
     *
     * @param float $p the parameter of the geometric distribution
     * @return int a random integer from a geometric distribution with success
     *         probability $p; or PHP_INT_MAX if
     *         $p is (nearly) equal to 1.0.
     * @throws \InvalidArgumentException if p < 0 or if p > 1
     */
    public function geometric(float $p): int
    {
        if ($p < 0.0 || $p > 1.0) {
            throw new \InvalidArgumentException(
                "probability p must be between 0.0 and 1.0: {$p}"
            );
        }

        // using algorithm given by Knuth
        return (int)ceil(log($this->randomizer->nextFloat()) / log(1.0 - $p));
    }

    /**
     * Returns a random integer from a Poisson distribution with mean &lambda;.
     *
     * @param float $lambda the mean of the Poisson distribution
     * @return int a random integer from a Poisson distribution with mean {@code lambda}
     * @throws \InvalidArgumentException if lambda > 0.0 or infinite
     */
    public function poisson(float $lambda): int
    {
        if ($lambda <= 0.0 || !is_finite($lambda)) {
            throw new \InvalidArgumentException(
                "lambda must be positive and finite: {$lambda}"
            );
        }

        // using algorithm given by Knuth
        // see http://en.wikipedia.org/wiki/Poisson_distribution
        $k = 0;
        $p = 1.0;
        $e = exp(-$lambda);
        do {
            $k++;
            $p = $this->randomizer->nextFloat();
        } while ($p >= $e);
        return $k - 1;
    }

    /**
     * Returns a random real number from a Pareto distribution with
     * shape parameter &alpha;.
     *
     * @param float $alpha shape parameter
     * @return float a random real number from a Pareto distribution with shape
     *         parameter {@code alpha}
     * @throws \InvalidArgumentException if alpha <= 0.0
     */
    public function pareto(float $alpha = 1.0): float
    {
        if ($alpha <= 0.0) {
            throw new \InvalidArgumentException(
                "alpha must be positive: {$alpha}"
            );
        }
        return pow(1 - $this->randomizer->nextFloat(), -1.0 / $alpha) - 1.0;
    }

    /**
     * Returns a random real number from the Cauchy distribution.
     *
     * @param float $x0 location parameter
     * @param float $gamma scale parameter
     * @return float a random real number from the Cauchy distribution.
     */
    public function cauchy(float $x0 = 0.0, float $gamma = 1.0): float
    {
        return $x0 + $gamma * tan(M_PI * ($this->randomizer->nextFloat() - 0.5));
    }

    /**
     * Returns a random integer from the specified discrete distribution.
     *
     * @template T of int|string
     * @param array<T, float> $probabilities probabilities the probability of occurrence of each integer
     * @return ?T a random integer from a discrete distribution:
     *          {@code i} with probability {@code probabilities[i]}
     * @throws \InvalidArgumentException if sum of array entries is not (very nearly) equal to {@code 1.0}
     *          or unless {@code probabilities[i] >= 0.0} for each index {@code i}
     */
    public function discreteFromProbabilities(array $probabilities): int|string|null
    {
        $sum = array_sum($probabilities);
        if (abs($sum - 1.0) > 1E-14) {
            throw new \InvalidArgumentException(
                "sum of probabilities must be 1: {$sum}"
            );
        }
        $r = $this->uniformFloat(0.0, $sum);
        $sum = 0.0;
        foreach ($probabilities as $key => $val) {
            if ($val < 0.0) {
                throw new \InvalidArgumentException(
                    "array entry {$key} must be non-negative: {$val}"
                );
            }
            $sum += $val;
            if ($sum > $r) {
                return $key;
            }
        }
        return array_key_last($probabilities);
    }

    /**
     * @template T of int|string
     * @param array<T, int> $frequencies
     * @return ?T
     */
    public function discreteFromFrequencies(array $frequencies): int|string|null
    {
        $sum = array_sum($frequencies);
        $probabilities = array_map(fn(int $v) => $v / $sum, $frequencies);
        return $this->discreteFromProbabilities($probabilities);
    }

    public function exponential(float $lambda = 1.0): float
    {
        if ($lambda <= 0.0) {
            throw new \InvalidArgumentException(
                "lambda must be positive: {$lambda}"
            );
        }
        return -log(1 - $this->randomizer->nextFloat()) / $lambda;
    }

    /**
     * @template T
     * @param T[] $a
     * @param int $lo
     * @param int|null $hi
     * @return T[]
     */
    public function shuffle(array $a, int $lo = 0, ?int $hi = null): array
    {
        $hi = $hi ?? count($a);
        if ($lo < 0 || $hi > count($a) || $lo > $hi) {
            throw new \InvalidArgumentException(
                "invalid subarray range: [$lo, $hi)"
            );
        }

        for ($i = $lo; $i < $hi; $i++) {
            $r = $this->uniformInt($i, $hi);
            $tmp = $a[$i];
            $a[$i] = $a[$r];
            $a[$r] = $tmp;
        }
        return $a;
    }

    /**
     * @param int $n
     * @param int|null $k
     * @return int[]
     */
    public function permutation(int $n, ?int $k = null): array
    {
        $k ??= $n;
        if ($n < 0) {
            throw new \InvalidArgumentException(
                "n must be non-negative: {$n}"
            );
        }

        if ($k < 0 || $k > $n) {
            throw new \InvalidArgumentException(
                "k must be between 0 and n: {$k}"
            );
        }

        $perm = array_fill(0, $k, 0);
        for ($i = 0; $i < $k; $i++) {
            $r = $this->uniformInt(0, $i + 1);
            $perm[$i] = $perm[$r];
            $perm[$r] = $i;
        }
        for ($i = $k; $i < $n; $i++) {
            $r = $this->uniformInt(0, $i + 1);
            if ($r < $k) {
                $perm[$r] = $i;
            }
        }
        return $perm;
    }
}