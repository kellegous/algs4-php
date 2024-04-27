<?php

namespace Kellegous\Algs4;

use Exception;
use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversFunction('parse_int'), CoversFunction('parse_float')]
class FunctionsTest extends TestCase
{
    /**
     * @return iterable<string, array{string, int|Exception}>
     */
    public static function parseIntTests(): iterable
    {
        yield 'valid' => ['123', 123];

        yield 'zero' => ['0', 0];

        yield 'zero padded' => ['000', new InputFormatException('invalid integer: 000')];

        yield 'invalid' => ['abc', new InputFormatException('invalid integer: abc')];
    }

    #[Test, DataProvider('parseIntTests')]
    public function parseInt(string $s, int|Exception $expected): void
    {
        if ($expected instanceof Exception) {
            self::expectExceptionObject($expected);
            parse_int($s);
        } else {
            self::assertEquals($expected, parse_int($s));
        }
    }

    /**
     * @return iterable<string, array{string, float|Exception}>
     */
    public static function parseFloatTests(): iterable
    {
        yield 'valid' => ['123.45', 123.45];

        yield 'integer' => ['123', 123.0];

        yield 'negative' => ['-123.45', -123.45];

        yield 'empty' => ['', new InputFormatException('invalid float: ')];

        yield 'zero padded' => ['00123.450', 123.45];

        yield 'invalid' => ['a.bc', new InputFormatException('invalid float: a.bc')];
    }

    #[Test, DataProvider('parseFloatTests')]
    public function parseFloat(string $s, float|Exception $expected): void
    {
        if ($expected instanceof Exception) {
            self::expectExceptionObject($expected);
            parse_float($s);
        } else {
            self::assertEquals($expected, parse_float($s));
        }
    }
}