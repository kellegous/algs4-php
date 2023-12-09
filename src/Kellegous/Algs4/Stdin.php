<?php declare(strict_types=1);

namespace Kellegous\Algs4;

class Stdin
{
    private static ?Scanner $instance = null;

    private function __construct()
    {
    }

    /**
     * @return Scanner
     */
    public static function get(): Scanner
    {
        if (self::$instance === null) {
            self::$instance = new Scanner(STDIN);
        }
        return self::$instance;
    }

    /**
     * @return bool
     */
    public static function isEmpty(): bool
    {
        return self::get()->isEmpty();
    }

    /**
     * @return string
     * @throws UnexpectedEndOfStreamException
     */
    public static function readString(): string
    {
        return self::get()->readString();
    }

    /**
     * @return string
     * @throws UnexpectedEndOfStreamException
     */
    public static function readLine(): string
    {
        return self::get()->readLine();
    }

    /**
     * @return int
     * @throws UnexpectedEndOfStreamException
     * @throws InputFormatException
     */
    public static function readInt(): int
    {
        return self::get()->readInt();
    }

    /**
     * @return float
     * @throws InputFormatException
     * @throws UnexpectedEndOfStreamException
     */
    public static function readFloat(): float
    {
        return self::get()->readFloat();
    }

    /**
     * @return bool
     * @throws InputFormatException
     * @throws UnexpectedEndOfStreamException
     */
    public static function readBool(): bool
    {
        return self::get()->readBool();
    }

    /**
     * @return string
     */
    public static function readAll(): string
    {
        return self::get()->readAll();
    }

    /**
     * @return string[]
     * @throws UnexpectedEndOfStreamException
     */
    public static function readStrings(): array
    {
        return self::get()->readStrings();
    }

    /**
     * @return string[]
     * @throws UnexpectedEndOfStreamException
     */
    public static function readLines(): array
    {
        return self::get()->readLines();
    }

    /**
     * @return int[]
     * @throws InputFormatException
     * @throws UnexpectedEndOfStreamException
     */
    public static function readInts(): array
    {
        return self::get()->readInts();
    }

    /**
     * @return float[]
     * @throws InputFormatException
     * @throws UnexpectedEndOfStreamException
     */
    public static function readFloats(): array
    {
        return self::get()->readFloats();
    }

    /**
     * @return bool[]
     * @throws InputFormatException
     * @throws UnexpectedEndOfStreamException
     */
    public static function readBools(): array
    {
        return self::get()->readBools();
    }
}