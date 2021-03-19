<?php


namespace Kellegous\Testing;


trait Goodies
{
    private static function captureException(
        \Closure $fn
    ): ?\Throwable {
        try {
            $fn();
        } catch (\Throwable $e) {
            return $e;
        }
        return null;
    }
}