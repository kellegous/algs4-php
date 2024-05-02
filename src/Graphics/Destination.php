<?php

declare(strict_types=1);

namespace Kellegous\Algs4\Graphics;

/**
 * An ouput destination for a drawing.
 */
final readonly class Destination
{
    /**
     * @param resource|string $destination
     */
    private function __construct(private mixed $destination)
    {
    }

    /**
     * Create a destination that writes to the given stream.
     * @param resource $destination
     * @return self
     */
    public static function toStream(mixed $destination): self
    {
        return new self($destination);
    }

    /**
     * Create a destination that writes to the given file.
     * @param string $path
     * @return self
     */
    public static function toFile(string $path): self
    {
        return new self($path);
    }

    /**
     * @return resource|string
     * @internal
     */
    public function get(): mixed
    {
        return $this->destination;
    }
}