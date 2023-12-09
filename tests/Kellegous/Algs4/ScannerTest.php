<?php

declare(strict_types=1);

namespace Kellegous\Algs4;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class ScannerTest extends TestCase
{
    /**
     * @param string $content
     * @return resource
     * @throws IOException
     */
    private static function streamWith(string $content): mixed
    {
        $data = base64_encode($content);
        $stream = fopen("data://text/plain;base64,$data", 'r');
        if ($stream === false) {
            throw new IOException('Unable to create stream');
        }
        return $stream;
    }

    /**
     * @return iterable<array{Scanner, string[]}>
     * @throws IOException
     */
    public static function readStringsTests(): iterable
    {
        yield 'empty' => [
            new Scanner(self::streamWith('')),
            [],
        ];

        yield 'one' => [
            new Scanner(self::streamWith('a')),
            ['a'],
        ];

        yield 'with spaces' => [
            new Scanner(self::streamWith('a b')),
            ['a', 'b'],
        ];

        yield 'with newlines' => [
            new Scanner(self::streamWith("a\nb\n")),
            ['a', 'b'],
        ];

        yield 'with multiple delimiters' => [
            new Scanner(self::streamWith("a \t\nb \t\n")),
            ['a', 'b'],
        ];

        yield 'delimiter crosses buffer' => [
            new Scanner(self::streamWith("a   b"), 3),
            ['a', 'b'],
        ];
    }

    /**
     * @param Scanner $scanner
     * @param string[] $expected
     * @return void
     * @throws UnexpectedEndOfStreamException
     * @throws IOException
     */
    #[Test, DataProvider('readStringsTests')]
    public function testReadStrings(
        Scanner $scanner,
        array $expected
    ): void {
        self::assertEquals($expected, $scanner->readStrings());
    }

    /**
     * @return iterable<array{Scanner, string[]}>
     * @throws IOException
     */
    public static function readLinesTests(): iterable
    {
        yield 'empty' => [
            new Scanner(self::streamWith('')),
            [],
        ];

        yield 'only new line' => [
            new Scanner(self::streamWith("\n")),
            [''],
        ];

        yield 'single line' => [
            new Scanner(self::streamWith("a")),
            ['a'],
        ];

        yield 'single line with newline' => [
            new Scanner(self::streamWith("a\n")),
            ['a'],
        ];

        yield 'empty lines' => [
            new Scanner(self::streamWith("a\n\n")),
            ['a', ''],
        ];
    }

    /**
     * @param Scanner $scanner
     * @param string[] $expected
     * @return void
     * @throws UnexpectedEndOfStreamException
     * @throws IOException
     */
    #[Test, DataProvider('readLinesTests')]
    public function testReadLines(
        Scanner $scanner,
        array $expected
    ): void {
        self::assertEquals($expected, $scanner->readLines());
    }
}