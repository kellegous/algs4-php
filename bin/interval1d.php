<?php

declare(strict_types=1);

namespace Kellegous\Algs4;

/**
 * Execution: php bin/interval1d.php
 */

require __DIR__ . '/../vendor/autoload.php';

$intervals = [
    Interval1D::fromMinMax(15.0, 33.0),
    Interval1D::fromMinMax(45.0, 60.0),
    Interval1D::fromMinMax(20.0, 70.0),
    Interval1D::fromMinMax(46.0, 55.0),
];

$out = Stdio::out();

$out->println("Unsorted");
foreach ($intervals as $interval) {
    $out->println($interval);
}
$out->println();

$out->println("Sorted by min endpoint");
usort($intervals, fn($a, $b) => $a->min() <=> $b->min());
foreach ($intervals as $interval) {
    $out->println($interval);
}
$out->println();

$out->println("Sorted by max endpoint");
usort($intervals, fn($a, $b) => $a->max() <=> $b->max());
foreach ($intervals as $interval) {
    $out->println($interval);
}
$out->println();

$out->println("Sorted by length");
usort($intervals, fn($a, $b) => $a->length() <=> $b->length());
foreach ($intervals as $interval) {
    $out->println($interval);
}
$out->println();