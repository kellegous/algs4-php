<?php

declare(strict_types=1);

namespace Kellegous\Algs4\Graphics;

use Closure;

/**
 * Canvas is a utility that imposes a floating point coordinate system over a drawing.
 */
final readonly class Canvas
{
    /**
     * @var Closure(float,float):array{int,int}
     */
    private Closure $transform;

    /**
     * @param Drawing $drawing
     * @param Closure(float,float):array{int,int}|null $transform
     * @param Color|null $background
     * @throws Exception
     */
    public function __construct(
        private Drawing $drawing,
        ?Closure $transform = null,
        ?Color $background = null
    ) {
        $background = $background ?? Color::white($drawing);
        $width = $drawing->width();
        $height = $drawing->height();
        $this->transform = $transform ?? fn($x, $y) => [
            (int)($x * $width),
            (int)($y * $height)
        ];
        $drawing->floodFill(0, 0, $background);
    }

    /**
     * @param float $x1
     * @param float $y1
     * @param float $x2
     * @param float $y2
     * @return Rectangle
     */
    public function rectangle(float $x1, float $y1, float $x2, float $y2): Rectangle
    {
        [$x1, $y1] = ($this->transform)($x1, $y1);
        [$x2, $y2] = ($this->transform)($x2, $y2);
        return $this->drawing->rectangle($x1, $y1, $x2, $y2);
    }

    public function ellipse(float $center_x, float $center_y, float $width, float $height): Ellipse
    {
        [$cx, $cy] = ($this->transform)($center_x, $center_y);
        [$wx, $wy] = ($this->transform)($center_x + $width, $center_y + $height);
        [$width, $height] = [$wx - $cx, $wy - $cy];
        return $this->drawing->ellipse(
            (int)$center_x,
            (int)$center_y,
            (int)$width,
            (int)$height
        );
    }

    /**
     * Set the color of the pixel under the point ($x, $y).
     *
     * @param float $x
     * @param float $y
     * @param Color $color
     * @return void
     * @throws Exception
     */
    public function point(float $x, float $y, Color $color): void
    {
        [$x, $y] = ($this->transform)($x, $y);
        $this->drawing->setPixel((int)$x, (int)$y, $color);
    }

    /**
     * @return Drawing
     */
    public function drawing(): Drawing
    {
        return $this->drawing;
    }
}