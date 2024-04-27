<?php

namespace Kellegous\Algs4;

use InvalidArgumentException;
use Kellegous\Algs4\Date\Month;
use RuntimeException;

final readonly class Date
{
    private function __construct(
        private Month $month,
        private int $day,
        private int $year
    ) {
    }

    public function month(): Month
    {
        return $this->month;
    }

    public function day(): int
    {
        return $this->day;
    }

    public function year(): int
    {
        return $this->year;
    }

    public static function fromYMD(Month $month, int $day, int $year): self
    {
        if ($year < 0) {
            throw new InvalidArgumentException("invalid date: year < 0");
        }

        if ($day < 1 || $day > $month->daysIn($year)) {
            throw new InvalidArgumentException(
                sprintf("invalid date: day out of range for %02d/%04d", $month->value + 1, $year)
            );
        }

        return new self($month, $day, $year);
    }

    public static function fromString(string $s): self
    {
        $parts = explode('/', $s);
        if (count($parts) !== 3) {
            throw new InvalidArgumentException("invalid date: {$s}");
        }

        try {
            $month = Month::tryFrom(self::parseInt($parts[0]) - 1);
            $day = self::parseInt($parts[1]);
            $year = self::parseInt($parts[2]);
        } catch (InputFormatException $e) {
            throw new InvalidArgumentException("invalid date: {$s}");
        }

        if ($month === null) {
            throw new InvalidArgumentException("invalid date: {$s}");
        }

        return self::fromYMD($month, $day, $year);
    }

    public static function compare(self $a, self $b): int
    {
        $y = $a->year <=> $b->year;
        if ($y !== 0) {
            return $y;
        }

        $m = Month::compare($a->month, $b->month);
        if ($m !== 0) {
            return $m;
        }

        return $a->day <=> $b->day;
    }

    /**
     * Equivalent to parse_int except integers with leading zeros are accepted as valid integers.
     *
     * @param string $s
     * @return int
     * @throws InputFormatException
     */
    private static function parseInt(string $s): int
    {
        // remove leading zeros (e.g. 00123 -> 123)
        $s = preg_replace('/^0+([^0]|0$)/', '\1', $s);
        if (!is_string($s)) {
            throw new RuntimeException("leading zero pattern failed");
        }
        return parse_int($s);
    }
}