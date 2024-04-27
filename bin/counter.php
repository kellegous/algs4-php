<?php

declare(strict_types=1);

use Kellegous\Algs4\Counter;
use Kellegous\Algs4\Random;
use Kellegous\Algs4\Stdio;
use Random\Engine\Mt19937;

require __DIR__ . '/../vendor/autoload.php';

/******************************************************************************
 *  Execution:    php bin/counter.php n trials
 *
 *  A mutable data type for an integer counter.
 *
 *  The test clients create n counters and performs trials increment
 *  operations on random counters.
 *
 * php bin/counter.php 6 600000
 *  100140 counter0
 *  100273 counter1
 *  99848 counter2
 *  100129 counter3
 *  99973 counter4
 *  99637 counter5
 *
 ******************************************************************************/
const USAGE = <<<USAGE
Usage: counter.php N trials
  N       number of counters
  trials  number of trials
USAGE;

function show_usage(): void
{
    fprintf(STDERR, USAGE);
    exit(1);
}

/**
 * @param string[] $args
 * @return array{int, int}
 */
function parse_args(array $args): array
{
    $count = count($args);
    if ($count !== 3) {
        show_usage();
    }

    $n = filter_var($args[1], FILTER_VALIDATE_INT);
    if ($n === false) {
        show_usage();
    }

    $trials = filter_var($args[2], FILTER_VALIDATE_INT);
    if ($trials === false) {
        show_usage();
    }

    return [(int)$n, (int)$trials];
}

[$n, $trials] = parse_args($argv);
$rng = Random::withEngine(new Mt19937());
$counters = [];
for ($i = 0; $i < $n; $i++) {
    $counters[] = new Counter("counter{$i}");
}
for ($i = 0; $i < $trials; $i++) {
    $counters[$rng->uniformInt(0, $n)]->increment();
}
foreach ($counters as $counter) {
    Stdio::out()->println($counter);
}