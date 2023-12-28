<?php

declare(strict_types=1);

namespace Kellegous\Algs4;

/**
 *
 */
final class Stdin
{
    /**
     * @var In|null
     */
    private static ?In $instance = null;

    private function __construct()
    {
    }

    /**
     * @return In
     */
    public static function get(): In
    {
        if (self::$instance === null) {
            self::$instance = new In(STDIN);
        }
        return self::$instance;
    }
}