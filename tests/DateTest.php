<?php

declare(strict_types=1);

namespace Kellegous\Algs4;

use Exception;
use InvalidArgumentException;
use Kellegous\Algs4\Date\Month;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(Date::class)]
class DateTest extends TestCase
{
    /**
     * @return iterable<string, array{string,Date|Exception}>
     */
    public static function fromStringTests(): iterable
    {
        yield 'valid non-leap year' => [
            '2/28/2021',
            Date::fromYMD(2021, Month::February, 28),
        ];

        yield 'valid leap year' => [
            '2/29/2020',
            Date::fromYMD(2020, Month::February, 29),
        ];

        yield 'invalid non-leap year' => [
            '2/29/2021',
            new InvalidArgumentException('invalid date: 2/29/2021'),
        ];

        yield 'jan 1' => [
            '1/1/1970',
            Date::fromYMD(1970, Month::January, 1),
        ];

        yield 'dec 31' => [
            '12/31/1970',
            Date::fromYMD(1970, Month::December, 31),
        ];

        yield 'zero padded' => [
            '02/02/0012',
            Date::fromYMD(12, Month::February, 2),
        ];

        yield 'empty' => [
            '',
            new InvalidArgumentException('invalid date: '),
        ];

        yield 'too few parts' => [
            '1/2',
            new InvalidArgumentException('invalid date: 1/2'),
        ];

        yield 'too many parts' => [
            '1/2/3/4',
            new InvalidArgumentException('invalid date: 1/2/3/4'),
        ];

        yield 'invalid year' => [
            '1/2/-3',
            new InvalidArgumentException('invalid date: 1/2/-3'),
        ];

        yield 'invalid month' => [
            '13/1/2021',
            new InvalidArgumentException('invalid date: 13/1/2021'),
        ];

        yield 'invalid day' => [
            '1/32/2021',
            new InvalidArgumentException('invalid date: 1/32/2021'),
        ];

        yield 'non-integer month' => [
            'a/1/2021',
            new InvalidArgumentException('invalid date: a/1/2021'),
        ];
    }

    #[Test, DataProvider('fromStringTests')]
    public function fromString(
        string $s,
        Date|Exception $expected
    ): void {
        if ($expected instanceof Exception) {
            self::expectExceptionObject($expected);
            Date::fromString($s);
        } else {
            self::assertEquals(
                0,
                Date::compare(
                    $expected,
                    Date::fromString($s)
                )
            );
        }
    }

    /**
     * @return iterable<string, array{Date, Date, int}>
     */
    public static function compareTests(): iterable
    {
        yield 'same' => [
            Date::fromYMD(2021, Month::January, 1),
            Date::fromYMD(2021, Month::January, 1),
            0,
        ];

        yield 'a in earlier year than b' => [
            Date::fromYMD(1992, Month::January, 1),
            Date::fromYMD(2021, Month::January, 1),
            -1,
        ];

        yield 'a in later year than b' => [
            Date::fromYMD(2021, Month::January, 1),
            Date::fromYMD(1992, Month::January, 1),
            1,
        ];

        yield 'a in earlier month than b' => [
            Date::fromYMD(2021, Month::January, 1),
            Date::fromYMD(2021, Month::February, 1),
            -1,
        ];

        yield 'a in later month than b' => [
            Date::fromYMD(2021, Month::February, 1),
            Date::fromYMD(2021, Month::January, 1),
            1,
        ];

        yield 'a is earlier day than b' => [
            Date::fromYMD(2021, Month::January, 1),
            Date::fromYMD(2021, Month::January, 2),
            -1,
        ];

        yield 'a is later day than b' => [
            Date::fromYMD(2021, Month::January, 2),
            Date::fromYMD(2021, Month::January, 1),
            1,
        ];
    }

    #[Test, DataProvider('compareTests')]
    public function compare(Date $a, Date $b, int $expected): void
    {
        self::assertEquals($expected, Date::compare($a, $b));
    }

    #[Test]
    public function basicState(): void
    {
        $date = Date::fromYMD(2021, Month::January, 1);
        self::assertEquals(Month::January, $date->month());
        self::assertEquals(1, $date->day());
        self::assertEquals(2021, $date->year());
    }
}