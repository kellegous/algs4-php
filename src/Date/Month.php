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

    case January = 0;
    case February = 1;
    case March = 2;
    case April = 3;
    case May = 4;
    case June = 5;
    case July = 6;
    case August = 7;
    case September = 8;
    case October = 9;
    case November = 10;
    case December = 11;

    private static function isLeapYear(int $year): bool
    {
        return $year % 400 == 0 || $year % 100 != 0 && $year % 4 == 0;
    }

    public function daysIn(int $year): int
    {
        if ($this === self::February && self::isLeapYear($year)) {
            return 29;
        }
        return self::DAYS[$this->value];
    }

    public static function compare(self $a, self $b): int
    {
        return $a->value <=> $b->value;
    }
}
