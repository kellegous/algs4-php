<?php

declare(strict_types=1);

namespace Kellegous\Algs4;

use InvalidArgumentException;
use SplFixedArray;

/**
 *  The {@code Vector} class represents a <em>d</em>-dimensional Euclidean vector.
 *  Vectors are immutable: their values cannot be changed after they are created.
 *  It includes methods for addition, subtraction,
 *  dot product, scalar product, unit vector, Euclidean norm, and the Euclidean
 *  distance between two vectors.
 *  <p>
 *  For additional documentation,
 *  see <a href="https://algs4.cs.princeton.edu/12oop">Section 1.2</a> of
 *  <i>Algorithms, 4th Edition</i> by Robert Sedgewick and Kevin Wayne.
 *
 * @author Robert Sedgewick
 * @author Kevin Wayne
 * @author Kelly Norton
 */
final readonly class Vector
{
    /**
     * @param SplFixedArray<float> $data
     */
    private function __construct(
        private SplFixedArray $data
    ) {
    }

    /**
     *  Initializes a vector from a list of floats.
     * @param float ...$values
     * @return self
     */
    public static function fromData(float ...$values): self
    {
        /** @var array<int, float> $values */
        return new self(
            SplFixedArray::fromArray($values, false)
        );
    }

    /**
     * Initializes a vector with dimension of {@code d} where each coordinate is {@code v}.
     * @param int $d
     * @param float $v
     * @return self
     */
    public static function withDimension(int $d, float $v = 0.0): self
    {
        $data = new SplFixedArray($d);
        for ($i = 0; $i < $d; $i++) {
            $data[$i] = $v;
        }
        return new self($data);
    }

    /**
     * Returns the ith cartesian coordinate.
     * @param int $i
     * @return float
     */
    public function cartesian(int $i): float
    {
        /** @var float $v */
        $v = $this->data[$i];
        return $v;
    }

    /**
     * Returns the sum of this vector and the specified vector.
     * @param Vector $that
     * @return self
     */
    public function plus(self $that): self
    {
        $n = self::dimensionMustMatch($this, $that);
        $data = new SplFixedArray($n);
        for ($i = 0; $i < $n; $i++) {
            $data[$i] = $this->data[$i] + $that->data[$i];
        }
        return new self($data);
    }

    /**
     * Asserts that the dimensions of the two vectors are equal and returns the dimension.
     * @param Vector $a
     * @param Vector $b
     * @return int
     * @throws InvalidArgumentException
     */
    private static function dimensionMustMatch(
        self $a,
        self $b,
    ): int {
        $na = $a->dimension();
        $nb = $b->dimension();
        if ($na !== $nb) {
            throw new InvalidArgumentException("dimensions do not agree");
        }
        return $na;
    }

    /**
     * Returns the dimension of this vector.
     * @return int
     */
    public function dimension(): int
    {
        return $this->data->count();
    }

    /**
     * Returns the Euclidean distance between this vector and the specified vector.
     * @param Vector $that
     * @return float
     */
    public function distanceTo(self $that): float
    {
        return $this->minus($that)->magnitude();
    }

    /**
     * Returns the magnitude of this vector. This is also known as the L2 norm or the Euclidean norm.
     * @return float
     */
    public function magnitude(): float
    {
        return sqrt($this->dot($this));
    }

    /**
     * Returns the dot product of this vector with the specified vector.
     * @param Vector $that
     * @return float
     */
    public function dot(self $that): float
    {
        $n = self::dimensionMustMatch($this, $that);
        $sum = 0.0;
        for ($i = 0; $i < $n; $i++) {
            $sum += $this->data[$i] * $that->data[$i];
        }
        return $sum;
    }

    /**
     * Returns the difference between this vector and the specified vector.
     * @param Vector $that
     * @return self
     */
    public function minus(self $that): self
    {
        $n = self::dimensionMustMatch($this, $that);
        $data = new SplFixedArray($n);
        for ($i = 0; $i < $n; $i++) {
            $data[$i] = $this->data[$i] - $that->data[$i];
        }
        return new self($data);
    }

    /**
     * Returns a unit vector in the direction of this vector.
     * @return self
     */
    public function direction(): self
    {
        $magnitude = $this->magnitude();
        if ($magnitude === 0.0) {
            throw new InvalidArgumentException('zero-vector has no direction');
        }
        return $this->scale(1.0 / $magnitude);
    }

    /**
     * Returns the scalar-vector product of this vector and the specified scalar
     * @param float $alpha
     * @return self
     */
    public function scale(float $alpha): self
    {
        $n = $this->dimension();
        $data = new SplFixedArray($n);
        for ($i = 0; $i < $n; $i++) {
            $data[$i] = $alpha * $this->data[$i];
        }
        return new self($data);
    }

    /**
     * Returns a string representation of this vector.
     * @return string
     */
    public function __toString(): string
    {
        $parts = [];
        for ($i = 0, $n = $this->dimension(); $i < $n; $i++) {
            $parts[] = sprintf('%0.2g', $this->data[$i]);
        }
        return sprintf("[%s]", implode(' ', $parts));
    }
}