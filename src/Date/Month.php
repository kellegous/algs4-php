<?php

namespace Kellegous\Algs4\Date;

enum Month: int
{
    private const DAYS = [
        31,
        28,
        31,
        30,
        31,
        30,
        31,
        31,
        30,
        31,
        30,
        31
    ];

    case January = 1;
    case February = 2;
    case March = 3;
    case April = 4;
    case May = 5;
    case June = 6;
    case July = 7;
    case August = 8;
    case September = 9;
    case October = 10;
    case November = 11;
    case December = 12;

    /**
     * Compares two months chronologically.
     *
     * @param Month $a
     * @param Month $b
     * @return int
     */
    public static function compare(self $a, self $b): int
    {
        return $a->value <=> $b->value;
    }

    /**
     * Returns the days in the month for the given year.
     *
     * @param int $year
     * @return int
     */
    public function daysIn(int $year): int
    {
        if ($this === self::February && self::isLeapYear($year)) {
            return 29;
        }
        return self::DAYS[$this->value - 1];
    }

    /**
     * Is the given year a leap year?
     *
     * @param int $year
     * @return bool
     */
    private static function isLeapYear(int $year): bool
    {
        return $year % 400 == 0 || $year % 100 != 0 && $year % 4 == 0;
    }
}
