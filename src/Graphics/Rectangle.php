<?php

declare(strict_types=1);

namespace Kellegous\Algs4\Graphics;

/*
 * Represents a drawable rectangle associated with a drawing.
 */

final readonly class Rectangle
{
    /**
     * @param Drawing $drawing
     * @param int $x1
     * @param int $y1
     * @param int $x2
     * @param int $y2
     */
    public function __construct(
        private Drawing $drawing,
        private int $x1,
        private int $y1,
        private int $x2,
        private int $y2,
    ) {
    }

    /**
     * Stroke the rectangle with the given color.
     * @param Color $color
     * @return void
     * @throws Exception
     */
    public function stroke(Color $color): void
    {
        if (!imagerectangle(
            $this->drawing->getImage(),
            $this->x1,
            $this->y1,
            $this->x2,
            $this->y2,
            $color->getID()
        )) {
            throw new Exception('unable to stroke rectangle');
        }
    }

    /**
     * Fill the rectangle with the given color.
     * @param Color $color
     * @return void
     * @throws Exception
     */
    public function fill(Color $color): void
    {
        if (!imagefilledrectangle(
            $this->drawing->getImage(),
            $this->x1,
            $this->y1,
            $this->x2,
            $this->y2,
            $color->getID()
        )) {
            throw new Exception('unable to fill rectangle');
        }
    }
}