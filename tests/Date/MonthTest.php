<?php

declare(strict_types=1);

namespace Kellegous\Algs4\Date;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(Month::class)]
class MonthTest extends TestCase
{
    /**
     * @return iterable<string, array{Month, int, int}>
     */
    public static function daysInTests(): iterable
    {
        yield 'January' => [Month::January, 2020, 31];

        yield 'February (leap year)' => [Month::February, 2020, 29];

        yield 'February (non-leap year)' => [Month::February, 2021, 28];

        yield 'March' => [Month::March, 2020, 31];

        yield 'April' => [Month::April, 2020, 30];

        yield 'May' => [Month::May, 2020, 31];

        yield 'June' => [Month::June, 2020, 30];

        yield 'July' => [Month::July, 2020, 31];

        yield 'August' => [Month::August, 2020, 31];

        yield 'September' => [Month::September, 2020, 30];

        yield 'October' => [Month::October, 2020, 31];

        yield 'November' => [Month::November, 2020, 30];

        yield 'December' => [Month::December, 2020, 31];
    }

    /**
     * @return iterable<string, array{Month, Month, int}>
     */
    public static function compareTests(): iterable
    {
        yield 'same' => [Month::January, Month::January, 0];

        yield 'a < b' => [Month::January, Month::July, -1];

        yield 'a > b' => [Month::December, Month::February, 1];
    }

    #[Test, DataProvider('daysInTests')]
    public function daysIn(Month $month, int $year, int $expected): void
    {
        self::assertEquals($expected, $month->daysIn($year));
    }

    #[Test, DataProvider('compareTests')]
    public function compare(Month $a, Month $b, int $expected): void
    {
        self::assertEquals($expected, Month::compare($a, $b));
    }
}