<?php

declare(strict_types=1);

namespace Kellegous\Algs4;

use Closure;
use Exception;
use Iterator;
use Kellegous\Algs4\Testing\ErrorStream;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(In::class)]
class InTest extends TestCase
{
    /**
     * @param string $content
     * @return resource
     * @throws IOException
     */
    private static function streamWith(string $content): mixed
    {
        $data = base64_encode($content);
        return self::openStream("data://text/plain;base64,$data", 'r');
    }

    /**
     * @param string $path
     * @param string $mode
     * @return resource
     * @throws IOException
     */
    private static function openStream(string $path, string $mode): mixed
    {
        $stream = fopen($path, $mode);
        if ($stream === false) {
            throw new IOException("unable to open stream: $path");
        }
        return $stream;
    }

    /**
     * @return iterable<array{In, string[]}>
     * @throws IOException
     */
    public static function readStringsTests(): iterable
    {
        yield 'empty' => [
            new In(self::streamWith('')),
            [],
        ];

        yield 'one' => [
            new In(self::streamWith('a')),
            ['a'],
        ];

        yield 'with spaces' => [
            new In(self::streamWith('a b')),
            ['a', 'b'],
        ];

        yield 'with newlines' => [
            new In(self::streamWith("a\nb\n")),
            ['a', 'b'],
        ];

        yield 'with multiple delimiters' => [
            new In(self::streamWith("a \t\nb \t\n")),
            ['a', 'b'],
        ];

        yield 'delimiter crosses buffer' => [
            new In(self::streamWith("a   b"), 3),
            ['a', 'b'],
        ];
    }

    /**
     * @param In $scanner
     * @param string[] $expected
     * @return void
     * @throws UnexpectedEndOfStreamException
     * @throws IOException
     */
    #[Test, DataProvider('readStringsTests')]
    public function readStrings(
        In $scanner,
        array $expected
    ): void {
        self::assertEquals(
            $expected,
            iterator_to_array($scanner->readStrings())
        );
    }

    /**
     * @return iterable<array{In, string[]}>
     * @throws IOException
     */
    public static function readLinesTests(): iterable
    {
        yield 'empty' => [
            new In(self::streamWith('')),
            [],
        ];

        yield 'only new line' => [
            new In(self::streamWith("\n")),
            [''],
        ];

        yield 'single line' => [
            new In(self::streamWith("a")),
            ['a'],
        ];

        yield 'single line with newline' => [
            new In(self::streamWith("a\n")),
            ['a'],
        ];

        yield 'empty lines' => [
            new In(self::streamWith("a\n\n")),
            ['a', ''],
        ];
    }

    /**
     * @param In $scanner
     * @param string[] $expected
     * @return void
     * @throws UnexpectedEndOfStreamException
     * @throws IOException
     */
    #[Test, DataProvider('readLinesTests')]
    public function readLines(
        In $scanner,
        array $expected
    ): void {
        self::assertEquals(
            $expected,
            iterator_to_array($scanner->readLines())
        );
    }

    /**
     * @return iterable<array{In, int[], Exception|null}>
     * @throws IOException
     */
    public static function readIntsTests(): iterable
    {
        yield 'empty' => [
            new In(self::streamWith('')),
            [],
            null,
        ];

        yield 'one value' => [
            new In(self::streamWith('42')),
            [42],
            null,
        ];

        yield 'one value w/ spaces' => [
            new In(self::streamWith(" 42 ")),
            [42],
            null,
        ];

        yield 'multiple values' => [
            new In(self::streamWith("-12 -45 -90\n")),
            [-12, -45, -90],
            null,
        ];

        yield 'invalid number' => [
            new In(self::streamWith("0xff")),
            [],
            new InputFormatException('unable to parse int: 0xff'),
        ];
    }

    /**
     * @param In $scanner
     * @param int[] $expected
     * @param Exception|null $exception
     * @return void
     * @throws IOException
     * @throws InputFormatException
     * @throws UnexpectedEndOfStreamException
     */
    #[Test, DataProvider('readIntsTests')]
    public function readInts(
        In $scanner,
        array $expected,
        ?Exception $exception = null
    ): void {
        $this->readMany(fn() => $scanner->readInts(), $expected, $exception);
    }

    /**
     * @template T
     * @param Closure():Iterator<T> $fn
     * @param T[] $expected
     * @param Exception|null $exception
     * @return void
     */
    private function readMany(
        Closure $fn,
        array $expected,
        ?Exception $exception
    ): void {
        if ($exception !== null) {
            self::expectExceptionObject($exception);
        }
        self::assertEquals($expected, iterator_to_array($fn()));
    }

    /**
     * @return iterable<array{In, float[], Exception|null}>
     * @throws IOException
     */
    public static function readFloatsTests(): iterable
    {
        yield 'empty' => [
            new In(self::streamWith('')),
            [],
            null,
        ];
    }

    /**
     * @param In $scanner
     * @param float[] $expected
     * @param Exception|null $exception
     * @return void
     * @throws IOException
     * @throws InputFormatException
     * @throws UnexpectedEndOfStreamException
     */
    #[Test, DataProvider('readFloatsTests')]
    public function readFloats(
        In $scanner,
        array $expected,
        ?Exception $exception = null
    ): void {
        $this->readMany(fn() => $scanner->readFloats(), $expected, $exception);
    }

    /**
     * @return iterable<array{In, bool[], ?Exception}>
     * @throws IOException
     */
    public static function readBoolsTests(): iterable
    {
        yield 'empty' => [
            new In(self::streamWith('')),
            [],
            null,
        ];

        yield 'one value' => [
            new In(self::streamWith('true')),
            [true],
            null,
        ];

        yield 'one value w/ spaces' => [
            new In(self::streamWith(" false ")),
            [false],
            null,
        ];

        yield 'multiple values' => [
            new In(self::streamWith("1 0 false true\n")),
            [true, false, false, true],
            null,
        ];

        yield 'invalid bool' => [
            new In(self::streamWith("yes")),
            [],
            new InputFormatException('unable to parse bool: yes'),
        ];
    }

    /**
     * @param In $scanner
     * @param bool[] $expected
     * @param Exception|null $exception
     * @return void
     * @throws IOException
     * @throws InputFormatException
     * @throws UnexpectedEndOfStreamException
     */
    #[Test, DataProvider('readBoolsTests')]
    public function readBools(
        In $scanner,
        array $expected,
        ?Exception $exception = null
    ): void {
        $this->readMany(
            fn() => $scanner->readBools(),
            $expected,
            $exception
        );
    }

    /**
     * @return iterable<array{In, Exception}>
     * @throws IOException
     * @throws Exception
     */
    public static function errorTests(): iterable
    {
        ErrorStream::register();
        try {
            yield 'read error' => [
                new In(self::openStream('error-after://foo', 'r')),
                new IOException('unable to read from stream'),
            ];
        } finally {
            ErrorStream::unregister();
        }
    }

    #[Test, DataProvider('errorTests')]
    public function errors(
        In $scanner,
        Exception $expected
    ): void {
        try {
            $scanner->readString();
            self::fail('Expected exception');
        } catch (Exception $e) {
            self::assertInstanceOf(get_class($expected), $e);
            self::assertEquals($expected->getMessage(), $e->getMessage());
        }
    }
}