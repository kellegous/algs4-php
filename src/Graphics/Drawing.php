<?php

declare(strict_types=1);

namespace Kellegous\Algs4\Graphics;

use GdImage;

/**
 *
 */
final readonly class Drawing
{
    /**
     * @var GdImage
     */
    private GdImage $im;

    /**
     * @param int $width
     * @param int $height
     * @param bool $with_alpha_blending
     * @param bool $with_antialiasing
     * @throws Exception
     */
    public function __construct(
        int $width,
        int $height,
        bool $with_alpha_blending = true,
        bool $with_antialiasing = true
    ) {
        $im = imagecreatetruecolor($width, $height);
        if ($im === false) {
            throw new Exception('unable to create image');
        }

        if (!imagealphablending($im, $with_alpha_blending)) {
            throw new Exception('unable to set alpha blending');
        }

        if (!imageantialias($im, $with_antialiasing)) {
            throw new Exception('unable to set antialiasing');
        }
        $this->im = $im;
    }

    /**
     * @return GdImage
     * @internal
     */
    public function getImage(): GdImage
    {
        return $this->im;
    }

    /**
     * @param Destination $dest
     * @param int $quality
     * @param int $filters
     * @return void
     * @throws Exception
     */
    public function writePNG(
        Destination $dest,
        int $quality = -1,
        int $filters = -1,
    ): void {
        if (!imagepng($this->im, $dest->get(), $quality, $filters)) {
            throw new Exception('unable to write PNG');
        }
    }

    /**
     * @param Destination $dest
     * @param int $quality
     * @return void
     * @throws Exception
     */
    public function writeJPEG(
        Destination $dest,
        int $quality = -1,
    ): void {
        if (!imagejpeg($this->im, $dest->get(), $quality)) {
            throw new Exception('unable to write JPEG');
        }
    }

    /**
     * @param int<0,255> $r
     * @param int<0,255> $g
     * @param int<0,255> $b
     * @return Color
     * @throws Exception
     */
    public function colorFromRGB(int $r, int $g, int $b): Color
    {
        return Color::fromRGB($this, $r, $g, $b);
    }

    /**
     * @param int<0,255> $r
     * @param int<0,255> $g
     * @param int<0,255> $b
     * @param int<0,255> $a
     * @return Color
     * @throws Exception
     */
    public function colorFromRGBA(int $r, int $g, int $b, int $a): Color
    {
        return Color::fromRGBA($this, $r, $g, $b, $a);
    }

    /**
     * @param int $x1
     * @param int $y1
     * @param int $x2
     * @param int $y2
     * @return Rectangle
     */
    public function rectangle(int $x1, int $y1, int $x2, int $y2): Rectangle
    {
        return new Rectangle($this, $x1, $y1, $x2, $y2);
    }

    /**
     * @param int $center_x
     * @param int $center_y
     * @param int $width
     * @param int $height
     * @return Ellipse
     */
    public function ellipse(int $center_x, int $center_y, int $width, int $height): Ellipse
    {
        return new Ellipse($this, $center_x, $center_y, $width, $height);
    }

    /**
     * @param int $x
     * @param int $y
     * @param Color $color
     * @return void
     * @throws Exception
     */
    public function floodFill(int $x, int $y, Color $color): void
    {
        if (!imagefill($this->im, $x, $y, $color->getID())) {
            throw new Exception('unable to flood fill');
        }
    }
}