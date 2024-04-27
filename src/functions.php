<?php

declare(strict_types=1);

namespace Kellegous\Algs4;

function parse_int(string $s): int
{
    $v = filter_var($s, FILTER_VALIDATE_INT);
    if ($v === false) {
        throw new InputFormatException("invalid integer: {$s}");
    }
    return $v;
}

function parse_float(string $s): float
{
    $v = filter_var($s, FILTER_VALIDATE_FLOAT);
    if ($v === false) {
        throw new InputFormatException("invalid float: {$s}");
    }
    return $v;
}
