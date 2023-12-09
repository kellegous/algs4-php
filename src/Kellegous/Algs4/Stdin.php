<?php declare(strict_types=1);

namespace Kellegous\Algs4;

final class Stdin
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
}