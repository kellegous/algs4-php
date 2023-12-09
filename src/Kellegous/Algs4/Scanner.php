<?php

namespace Kellegous\Algs4;

use Closure;
use Generator;
use InvalidArgumentException;
use RuntimeException;

class Scanner
{
    private const WHITESPACE = '/\s+/';

    private const DEFAULT_BUFFER_SIZE = 4096;

    /**
     * @param resource $stream
     */
    private mixed $stream;

    /**
     * @var string
     */
    private string $buffer = '';

    /**
     * @var int<0,max>
     */
    private int $buffer_size;

    /**
     * @param mixed $stream
     * @param int $buffer_size
     */
    public function __construct(
        mixed $stream,
        int $buffer_size = self::DEFAULT_BUFFER_SIZE
    ) {
        if ($buffer_size < 0) {
            throw new InvalidArgumentException("buffer size must be >= 0");
        }
        $this->stream = $stream;
        $this->buffer_size = $buffer_size;
    }

    /**
     * @return bool
     * @throws IOException
     */
    public function isEmpty(): bool
    {
        if ($this->buffer === '') {
            $this->expandBuffer();
        }
        return $this->buffer === '' && feof($this->stream);
    }

    /**
     * @param string $pattern
     * @param bool $discard_leading
     * @return string
     * @throws IOException
     * @throws UnexpectedEndOfStreamException
     */
    private function readUntil(
        string $pattern,
        bool $discard_leading
    ): string {
        while (true) {
            if ($this->isEmpty()) {
                throw new UnexpectedEndOfStreamException();
            }

            $matches = null;
            $status = preg_match($pattern, $this->buffer, $matches, PREG_OFFSET_CAPTURE);
            if ($status === false) {
                throw new RuntimeException("match failure");
            } elseif ($status === 0) {
                // if we're at the end of the stream, just return what is left
                if (feof($this->stream)) {
                    return $this->takeBuffer();
                }

                // otherwise, expand the buffer and try again
                $this->expandBuffer();
                continue;
            }

            $a = $matches[0][1];
            $b = $matches[0][1] + strlen($matches[0][0]);
            if ($discard_leading && $a === 0) {
                $this->buffer = substr($this->buffer, $b);
                continue;
            }

            // if the delimiter is at the end of the buffer, we need to expand the buffer and try again because the
            // delimiter may continue beyond the buffer.
            if ($b === strlen($this->buffer) && !feof($this->stream)) {
                $this->expandBuffer();
                continue;
            }

            $result = substr($this->buffer, 0, $a);
            $this->buffer = substr($this->buffer, $b);
            return $result;
        }
    }

    /**
     * @return string
     * @throws UnexpectedEndOfStreamException
     * @throws IOException
     */
    public function readString(): string
    {
        return $this->readUntil(self::WHITESPACE, true);
    }

    /**
     * @return string
     * @throws UnexpectedEndOfStreamException
     * @throws IOException
     */
    public function readLine(): string
    {
        return $this->readUntil('/\n/', false);
    }

    /**
     * @return int
     * @throws InputFormatException
     * @throws UnexpectedEndOfStreamException
     * @throws IOException
     */
    public function readInt(): int
    {
        $s = $this->readString();
        if (filter_var($s, FILTER_VALIDATE_INT) === false) {
            throw new InputFormatException("unable to parse int: $s");
        }
        return intval($s);
    }

    /**
     * @return float
     * @throws InputFormatException
     * @throws UnexpectedEndOfStreamException
     * @throws IOException
     */
    public function readFloat(): float
    {
        $s = $this->readString();
        if (filter_var($s, FILTER_VALIDATE_FLOAT) === false) {
            throw new InputFormatException("unable to parse float: $s");
        }
        return floatval($s);
    }

    /**
     * @return bool
     * @throws InputFormatException
     * @throws UnexpectedEndOfStreamException
     * @throws IOException
     */
    public function readBool(): bool
    {
        $s = $this->readString();
        switch (strtolower($s)) {
            case 'true':
            case '1':
                return true;
            case 'false':
            case '0':
                return false;
        }
        throw new InputFormatException("unable to parse bool: $s");
    }

    /**
     * @return string
     * @throws IOException
     */
    public function readAll(): string
    {
        $data = stream_get_contents($this->stream);
        if ($data === false) {
            throw new IOException("unable to read from stream");
        }
        return $this->takeBuffer() . $data;
    }

    /**
     * @template T
     *
     * @param Closure():T $fn
     *
     * @return Generator<T>
     * @throws IOException
     */
    private function readMany(Closure $fn): Generator
    {
        while (!$this->isEmpty()) {
            yield $fn();
        }
    }

    /**
     * @return string[]
     *
     * @throws UnexpectedEndOfStreamException
     * @throws IOException
     */
    public function readStrings(): array
    {
        return iterator_to_array(
            $this->readMany(fn() => $this->readString())
        );
    }

    /**
     * @return string[]
     *
     * @throws UnexpectedEndOfStreamException
     * @throws IOException
     */
    public function readLines(): array
    {
        return iterator_to_array(
            $this->readMany(fn() => $this->readLine())
        );
    }

    /**
     * @return int[]
     *
     * @throws InputFormatException
     * @throws UnexpectedEndOfStreamException
     * @throws IOException
     */
    public function readInts(): array
    {
        return iterator_to_array(
            $this->readMany(fn() => $this->readInt())
        );
    }

    /**
     * @return float[]
     *
     * @throws InputFormatException
     * @throws UnexpectedEndOfStreamException
     * @throws IOException
     */
    public function readFloats(): array
    {
        return iterator_to_array(
            $this->readMany(fn() => $this->readFloat())
        );
    }

    /**
     * @return bool[]
     *
     * @throws InputFormatException
     * @throws UnexpectedEndOfStreamException
     * @throws IOException
     */
    public function readBools(): array
    {
        return iterator_to_array(
            $this->readMany(fn() => $this->readBool())
        );
    }

    /**
     * @return void
     * @throws IOException
     */
    private function expandBuffer(): void
    {
        $data = fread($this->stream, $this->buffer_size);
        if ($data === false) {
            throw new IOException("unable to read from stream");
        }
        $this->buffer .= $data;
    }

    /**
     * @return string
     */
    private function takeBuffer(): string
    {
        $buffer = $this->buffer;
        $this->buffer = '';
        return $buffer;
    }
}