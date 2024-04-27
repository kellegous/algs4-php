<?php

namespace Kellegous\Algs4;

use InvalidArgumentException;
use Kellegous\Algs4\Date\Month;
use RuntimeException;

final readonly class Date
{
    private function __construct(
        private int $year,
        private Month $month,
        private int $day,
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

    public function next(): self
    {
        $y = $this->year;
        $m = $this->month;
        $d = $this->day;

        if (self::isValid($y, $m, $d + 1)) {
            return new self($y, $m, $d + 1);
        }

        $next_month = Month::tryFrom($m->value + 1);
        if (self::isValid($y, $next_month, 1)) {
            return new self($y, $next_month, 1);
        }

        return new self($y + 1, Month::January, 1);
    }

    public function __toString(): string
    {
        return self::format($this->year, $this->month, $this->day);
    }

    public static function fromYMD(int $year, Month $month, int $day): self
    {
        if (!self::isValid($year, $month, $day)) {
            throw new InvalidArgumentException(
                sprintf("invalid date: %s", self::format($year, $month, $day))
            );
        }

        return new self($year, $month, $day);
    }

    private static function format(int $year, Month $month, int $day): string
    {
        return sprintf("%02d/%02d/%04d", $month->value + 1, $day, $year);
    }

    /**
     * @param int $year
     * @param ?Month $month
     * @param int $day
     * @return bool
     *
     * @phpstan-assert-if-true Month $month
     */
    private static function isValid(int $year, ?Month $month, int $day): bool
    {
        return $year >= 0 && $month !== null && $day >= 1 && $day <= $month->daysIn($year);
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

        if (!self::isValid($year, $month, $day)) {
            throw new InvalidArgumentException("invalid date: {$s}");
        }

        return new self($year, $month, $day);
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