<?php

declare(strict_types=1);

use Kellegous\Algs4\Random;
use Random\Engine\Mt19937;

require 'vendor/autoload.php';

const USAGE = "Usage: php random.php <n> [seed]\n";

/**
 * Parse n and seed from the command line arguments.
 *
 * @param string[] $args
 * @return array{0: int, 1: int|null}
 */
function parseArgs(array $args): array
{
    $count = count($args);
    if ($count < 2) {
        return [10, null];
    }

    $n = filter_var($args[1], FILTER_VALIDATE_INT);
    if ($n === false) {
        fprintf(STDERR, USAGE);
        exit(1);
    }

    if ($count < 3) {
        return [$n, null];
    }

    $seed = filter_var($args[2], FILTER_VALIDATE_INT);
    if ($seed === false) {
        fprintf(STDERR, USAGE);
        exit(1);
    }

    return [$n, $seed];
}

[$n, $seed] = parseArgs($argv);
$random = Random::withEngine(new Mt19937($seed));
$probabilities = [0.5, 0.3, 0.1, 0.1];
$frequencies = [5, 3, 1, 1];
$a = ['A', 'B', 'C', 'D', 'E', 'F', 'G'];
for ($i = 0; $i < $n; $i++) {
    printf(
        "%2d %8.5f %5s %7.5f %1d %1d [%s]\n",
        $random->uniformInt(0, 100),
        $random->uniformFloat(10.0, 99.0),
        $random->bernoulli(0.5) ? 'true' : 'false',
        $random->gaussian(9.0, 0.2),
        $random->discreteFromProbabilities($probabilities),
        $random->discreteFromFrequencies($frequencies),
        implode(', ', $random->shuffle($a))
    );
}
