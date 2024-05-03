<?php

declare(strict_types=1);

namespace Kellegous\Algs4\Graphics;

/**
 * Representation of an RGBA color.
 */
final readonly class Color
{
    /**
     * @param int $id
     */
    private function __construct(
        private int $id
    ) {
    }

    /**
     * Create a new opaque color from the given RGB values.
     * @param Drawing $drawing
     * @param int<0,255> $r
     * @param int<0,255> $g
     * @param int<0,255> $b
     * @param int<0,255> $a
     * @return self
     * @throws Exception
     */
    public static function fromRGBA(
        Drawing $drawing,
        int $r,
        int $g,
        int $b,
        int $a,
    ): self {
        $c = imagecolorallocatealpha($drawing->getImage(), $r, $g, $b, $a >> 1);
        if ($c === false) {
            throw new Exception('unable to allocate color');
        }
        return new self($c);
    }

    public static function white(Drawing $drawing): self
    {
        return self::fromRGB($drawing, 255, 255, 255);
    }

    /**
     * Create a new opaque color from the given RGB values.
     * @param Drawing $drawing
     * @param int<0,255> $r
     * @param int<0,255> $g
     * @param int<0,255> $b
     * @return self
     * @throws Exception
     * @see Drawing::colorFromRGB()
     */
    public static function fromRGB(
        Drawing $drawing,
        int $r,
        int $g,
        int $b,
    ): self {
        $c = imagecolorallocate($drawing->getImage(), $r, $g, $b);
        if ($c === false) {
            throw new Exception('unable to allocate color');
        }
        return new self($c);
    }

    public static function black(Drawing $drawing): self
    {
        return self::fromRGB($drawing, 0, 0, 0);
    }

    /**
     * Get the GD color index. This will be the color as an integer.
     * @return int
     * @internal
     */
    public function getID(): int
    {
        return $this->id;
    }

    /**
     * Get the red component of the color.
     * @return int<0,255>
     */
    public function red(): int
    {
        return ($this->id >> 16) & 0xff;
    }

    /**
     * Get the green component of the color.
     * @return int<0,255>
     */
    public function green(): int
    {
        return ($this->id >> 8) & 0xff;
    }

    /**
     * Get the blue component of the color.
     * @return int<0,255>
     */
    public function blue(): int
    {
        return $this->id & 0xff;
    }

    /**
     * Get the alpha component of the color.
     * @return int<0,255>
     */
    public function alpha(): int
    {
        $a = (($this->id >> 24) & 0xff) << 1;
        assert($a >= 0 && $a <= 255);
        return $a;
    }
}