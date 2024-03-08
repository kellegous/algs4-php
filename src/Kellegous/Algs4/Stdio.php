<?php

declare(strict_types=1);

namespace Kellegous\Algs4;

final class Stdio
{
    /**
     * @var In|null
     */
    private static ?In $in = null;

    /**
     * @var Out|null
     */
    private static ?Out $err = null;

    /**
     * @var Out|null
     */
    private static ?Out $out = null;

    private function __construct()
    {
    }

    public static function in(): In
    {
        if (self::$in === null) {
            self::$in = new In(STDIN);
        }
        return self::$in;
    }

    public static function err(): Out
    {
        if (self::$err === null) {
            self::$err = new Out(STDERR);
        }
        return self::$err;
    }

    public static function out(): Out
    {
        if (self::$out === null) {
            self::$out = new Out(STDOUT);
        }
        return self::$out;
    }
}