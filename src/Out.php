<?php

namespace Kellegous\Algs4;

use InvalidArgumentException;

final class Out
{
    /**
     * @param resource $stream
     * @throws InvalidArgumentException
     */
    public function __construct(
        private mixed $stream
    ) {
    }

    /**
     * @return void
     * @throws IOException
     */
    public function close(): void
    {
        if (!fclose($this->stream)) {
            throw new IOException("unable to close stream");
        }
    }

    /**
     * @return void
     * @throws IOException
     */
    public function flush(): void
    {
        if (!fflush($this->stream)) {
            throw new IOException("unable to flush stream");
        }
    }

    /**
     * @param mixed $value
     * @return void
     * @throws IOException
     */
    public function print(mixed $value): void
    {
        if (!fwrite($this->stream, (string)$value)) {
            throw new IOException("unable to write to stream");
        }
    }

    /**
     * @param mixed $v
     * @return void
     * @throws IOException
     */
    public function println(mixed $v = ''): void
    {
        $this->print($v . "\n");
    }

    /**
     * @param string $format
     * @param mixed ...$args
     * @return void
     * @throws IOException
     */
    public function printf(string $format, mixed ...$args): void
    {
        $this->print(sprintf($format, ...$args));
    }

    public static function toFile(string $filename): self
    {
        $stream = fopen($filename, 'w');
        if ($stream === false) {
            throw new IOException("unable to open file");
        }
        return new self($stream);
    }
}