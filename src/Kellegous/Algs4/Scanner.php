<?php

namespace Kellegous\Algs4;

class Scanner
{
    private const WHITESPACE = '/\s+/';

    private const BUFFER_SIZE = 4096;

    /**
     * @param resource $stream
     */
    private mixed $stream;

    private string $buffer = '';

    public function __construct(mixed $stream)
    {
        $this->stream = $stream;
    }

    public function isEmpty(): bool
    {
        if ($this->buffer === '') {
            $this->expandBuffer();
        }
        return feof($this->stream);
    }

    private function readUntil(
        string $pattern,
        bool   $discard_leading
    ): string
    {
        while (true) {
            if ($this->isEmpty()) {
                throw new UnexpectedEndOfStreamException();
            }

            $matches = null;
            $status = preg_match($pattern, $this->buffer, $matches, PREG_OFFSET_CAPTURE);
            if ($status === false) {
                throw new \RuntimeException("match failure");
            } elseif ($status === 0) {
                $this->expandBuffer();
                continue;
            }

            $a = $matches[0][1];
            $b = $matches[0][1] + strlen($matches[0][0]);
            if ($discard_leading && $a === 0) {
                $this->buffer = substr($this->buffer, $b);
                continue;
            }

            $result = substr($this->buffer, 0, $a);
            $this->buffer = substr($this->buffer, $b);
            return $result;
        }
    }

    /**
     * @return string
     *
     * @throws UnexpectedEndOfStreamException
     */
    public function readString(): string
    {
        return $this->readUntil(self::WHITESPACE, true);
    }

    /**
     * @return string
     *
     * @throws UnexpectedEndOfStreamException
     */
    public function readLine(): string
    {
        return $this->readUntil('/\n/', false);
    }

    /**
     * @return int
     *
     * @throws InputFormatException
     * @throws UnexpectedEndOfStreamException
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
     *
     * @throws InputFormatException
     * @throws UnexpectedEndOfStreamException
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
     *
     * @throws InputFormatException
     * @throws UnexpectedEndOfStreamException
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
     */
    public function readAll(): string
    {
        $buffer = $this->buffer;
        $this->buffer = '';
        return $buffer . stream_get_contents($this->stream);
    }

    /**
     * @template T
     *
     * @param \Closure():T $fn
     *
     * @return \Generator<T>
     */
    private function readMany(\Closure $fn): \Generator
    {
        while (!$this->isEmpty()) {
            yield $fn();
        }
    }

    /**
     * @return string[]
     *
     * @throws UnexpectedEndOfStreamException
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
     */
    public function readBools(): array
    {
        return iterator_to_array(
            $this->readMany(fn() => $this->readBool())
        );
    }

    private function expandBuffer(): void
    {
        $data = fread($this->stream, self::BUFFER_SIZE);
        if ($data === false) {
            throw new IOException("unable to read from stream");
        }
        $this->buffer .= $data;
    }
}