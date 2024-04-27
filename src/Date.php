<?php

namespace Kellegous\Algs4;

use InvalidArgumentException;
use Kellegous\Algs4\Date\Month;
use RuntimeException;

/**
 *  The {@code Date} class is an immutable data type to encapsulate a
 *  date (day, month, and year).
 *  <p>
 *  For additional documentation,
 *  see <a href="https://algs4.cs.princeton.edu/12oop">Section 1.2</a> of
 *  <i>Algorithms, 4th Edition</i> by Robert Sedgewick and Kevin Wayne.
 *
 * @author Robert Sedgewick
 * @author Kevin Wayne
 * @author Kelly Norton
 */
final readonly class Date
{
    /**
     * Initializes a new date from the month, day, and year.
     *
     * @param int $year
     * @param Month $month
     * @param int $day
     * /
     */
    private function __construct(
        private int $year,
        private Month $month,
        private int $day,
    ) {
    }

    /**
     * Initializes a new date from the month, day, and year.
     *
     * @param int $year
     * @param Month $month
     * @param int $day
     * @return self
     * @throws InvalidArgumentException
     */
    public static function fromYMD(int $year, Month $month, int $day): self
    {
        if (!self::isValid($year, $month, $day)) {
            throw new InvalidArgumentException(
                sprintf("invalid date: %s", self::format($year, $month, $day))
            );
        }

        return new self($year, $month, $day);
    }

    /**
     * Would the date made of this year, month and day be valid?
     *
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

    /**
     * Format the date as a string in the format MM/DD/YYYY.
     *
     * @param int $year
     * @param Month $month
     * @param int $day
     * @return string
     */
    private static function format(int $year, Month $month, int $day): string
    {
        return sprintf("%02d/%02d/%04d", $month->value, $day, $year);
    }

    /**
     * Initializes new date specified as a string in form MM/DD/YYYY.
     *
     * @param string $s
     * @return self
     * @throws InvalidArgumentException
     */
    public static function fromString(string $s): self
    {
        $parts = explode('/', $s);
        if (count($parts) !== 3) {
            throw new InvalidArgumentException("invalid date: {$s}");
        }

        try {
            $month = Month::tryFrom(self::parseInt($parts[0]));
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

    /**
     * Return the month.
     *
     * @return Month
     */
    public function month(): Month
    {
        return $this->month;
    }

    /**
     * Returns the day.
     *
     * @return int
     */
    public function day(): int
    {
        return $this->day;
    }

    /**
     * Returns the year.
     *
     * @return int
     */
    public function year(): int
    {
        return $this->year;
    }

    /**
     * Returns the next date in the calendar.
     *
     * @return self
     */
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

    /**
     * Is the given date for this date?
     *
     * @param Date $that
     * @return bool
     */
    public function isBefore(self $that): bool
    {
        return self::compare($this, $that) < 0;
    }

    /**
     * Compares two dates chronologically.
     *
     * @param Date $a
     * @param Date $b
     * @return int
     */
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
     * Is the given date after this date?
     *
     * @param Date $that
     * @return bool
     */
    public function isAfter(self $that): bool
    {
        return self::compare($this, $that) > 0;
    }

    /**
     * Returns a string representation of the Date in the format MM/DD/YYYY.
     *
     * @return string
     */
    public function __toString(): string
    {
        return self::format($this->year, $this->month, $this->day);
    }
}