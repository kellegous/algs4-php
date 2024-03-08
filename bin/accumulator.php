<?php

declare(strict_types=1);

use Kellegous\Algs4\Accumulator;
use Kellegous\Algs4\Stdio;

require __DIR__ . '/../vendor/autoload.php';

$accumulator = new Accumulator();
foreach (Stdio::in()->readFloats() as $x) {
    $accumulator->add($x);
}

Stdio::out()->printf("n      = %d\n", $accumulator->count());
Stdio::out()->printf("mean   = %.5f\n", $accumulator->mean());
Stdio::out()->printf("stddev = %.5f\n", $accumulator->standardDeviation());
Stdio::out()->printf("var    = %.5f\n", $accumulator->variance());
Stdio::out()->printf("%s\n", $accumulator);
