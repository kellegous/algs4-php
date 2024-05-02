<?php

declare(strict_types=1);

namespace Kellegous\Algs4\Graphics;

/**
 * Represents a drawable ellipse associated with a drawing.
 */
final readonly class Ellipse
{
    /**
     * @param Drawing $drawing
     * @param int $center_x
     * @param int $center_y
     * @param int $width
     * @param int $height
     */
    public function __construct(
        private Drawing $drawing,
        private int $center_x,
        private int $center_y,
        private int $width,
        private int $height,
    ) {
    }

    /**
     * @param Color $color
     * @return void
     * @throws Exception
     */
    public function stroke(Color $color): void
    {
        if (!imageellipse(
            $this->drawing->getImage(),
            $this->center_x,
            $this->center_y,
            $this->width,
            $this->height,
            $color->getID()
        )) {
            throw new Exception('unable to stroke ellipse');
        }
    }

    /**
     * @param Color $color
     * @return void
     * @throws Exception
     */
    public function fill(Color $color): void
    {
        if (!imagefilledellipse(
            $this->drawing->getImage(),
            $this->center_x,
            $this->center_y,
            $this->width,
            $this->height,
            $color->getID()
        )) {
            throw new Exception('unable to fill ellipse');
        }
    }
}