<?php

namespace Kellegous\Algs4;

use Closure;
use Generator;
use RuntimeException;

/**
 *
 */
final class In
{
    private const string WHITESPACE = '/\s+/';
    private const string NEWLINE = '/\n/';

    private const int DEFAULT_BUFFER_SIZE = 4096;

    /**
     * @var string
     */
    private string $buffer = '';

    /**
     * @param resource $stream
     * @param int<0,max> $buffer_size
     */
    public function __construct(
        private readonly mixed $stream,
        private readonly int $buffer_size = self::DEFAULT_BUFFER_SIZE
    ) {
    }

    /**
     * @param string $pattern
     * @return string|null
     * @throws IOException
     */
    private function readUntil(string $pattern): ?string
    {
        while ($this->buffer !== '' || !feof($this->stream)) {
            $matches = null;
            $status = preg_match($pattern, $this->buffer, $matches, PREG_OFFSET_CAPTURE);
            if ($status === false) {
                throw new RuntimeException("match failure");
            } elseif ($status === 0) {
                if (!$this->expandBuffer()) {
                    return $this->takeBuffer();
                }
                continue;
            }

            $a = $matches[0][1];
            $b = $matches[0][1] + strlen($matches[0][0]);

            // if the delimiter is at the end of the buffer, we need to expand the buffer and try again because the
            // delimiter may continue beyond the buffer.
            if ($b === strlen($this->buffer)
                && $pattern === self::WHITESPACE
                && $this->expandBuffer()) {
                continue;
            }

            $result = substr($this->buffer, 0, $a);
            $this->buffer = substr($this->buffer, $b);
            return $result;
        }

        return null;
    }

    /**
     * @return ?string
     * @throws IOException
     */
    public function readString(): ?string
    {
        do {
            $s = $this->readUntil(self::WHITESPACE);
        } while ($s === '');
        return $s;
    }

    /**
     * @return ?string
     * @throws IOException
     */
    public function readLine(): ?string
    {
        return $this->readUntil(self::NEWLINE);
    }

    /**
     * @return ?int
     * @throws InputFormatException
     * @throws IOException
     */
    public function readInt(): ?int
    {
        $s = $this->readString();
        if ($s === null) {
            return null;
        }
        $v = filter_var($s, FILTER_VALIDATE_INT);
        if ($v === false) {
            throw new InputFormatException("unable to parse int: $s");
        }
        return $v;
    }

    /**
     * @return ?float
     * @throws InputFormatException
     * @throws IOException
     */
    public function readFloat(): ?float
    {
        $s = $this->readString();
        if ($s === null) {
            return null;
        }
        $v = filter_var($s, FILTER_VALIDATE_FLOAT);
        if ($v === false) {
            throw new InputFormatException("unable to parse float: $s");
        }
        return $v;
    }

    /**
     * @return ?bool
     * @throws InputFormatException
     * @throws IOException
     */
    public function readBool(): ?bool
    {
        $s = $this->readString();
        if ($s === null) {
            return null;
        }
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
     * @param Closure():(T|null) $fn
     *
     * @return Generator<T>
     */
    private function readMany(Closure $fn): Generator
    {
        while (true) {
            $v = $fn();
            if ($v === null) {
                return;
            }
            yield $v;
        }
    }

    /**
     * @return Generator<string>
     * @throws IOException
     */
    public function readStrings(): Generator
    {
        return $this->readMany(fn() => $this->readString());
    }

    /**
     * @return Generator<string>
     *
     * @throws UnexpectedEndOfStreamException
     * @throws IOException
     */
    public function readLines(): Generator
    {
        return $this->readMany(fn() => $this->readLine());
    }

    /**
     * @return Generator<int>
     * @throws InputFormatException
     * @throws IOException
     */
    public function readInts(): Generator
    {
        return $this->readMany(fn() => $this->readInt());
    }

    /**
     * @return Generator<float>
     *
     * @throws InputFormatException
     * @throws IOException
     */
    public function readFloats(): Generator
    {
        return $this->readMany(fn() => $this->readFloat());
    }

    /**
     * @return Generator<bool>
     *
     * @throws InputFormatException
     * @throws IOException
     */
    public function readBools(): Generator
    {
        return $this->readMany(fn() => $this->readBool());
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
     * @return bool
     * @throws IOException
     */
    private function expandBuffer(): bool
    {
        if (feof($this->stream)) {
            return false;
        }

        $data = fread($this->stream, $this->buffer_size);
        if ($data === false) {
            throw new IOException("unable to read from stream");
        }
        $this->buffer .= $data;
        return $data !== '';
    }

    /**
     * @return string|null
     */
    private function takeBuffer(): ?string
    {
        $buffer = $this->buffer;
        $this->buffer = '';
        return $buffer === '' ? null : $buffer;
    }

    /**
     * @param string $filename
     * @return self
     * @throws IOException
     */
    public static function fromFile(string $filename): self
    {
        $stream = fopen($filename, 'r');
        if ($stream === false) {
            throw new IOException("unable to open file: $filename");
        }
        return new self($stream);
    }
}