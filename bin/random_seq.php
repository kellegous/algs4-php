<?php

declare(strict_types=1);

use Kellegous\Algs4\Random;
use Kellegous\Algs4\Stdio;
use Random\Engine\Mt19937;

require __DIR__ . '/../vendor/autoload.php';

/**
 * random_seq.php is a client that prints out a pseudorandom sequence of real
 * numbers in a given range.
 *
 * Prints N numbers between lo and hi.
 *
 * % php random_seq.php 5 100.0 200.0
 * 123.43
 * 153.13
 * 144.38
 * 155.18
 * 104.02
 *
 *  <p>
 *  For additional documentation, see <a href="https://algs4.cs.princeton.edu/11model">Section 1.1</a> of
 *  <i>Algorithms, 4th Edition</i> by Robert Sedgewick and Kevin Wayne.
 * </p>
 *
 * @author Robert Sedgewick
 * @author Kevin Wayne
 * @author Kelly Norton
 */
const USAGE = <<<USAGE
Usage: random_seq.php N [lo hi]
  N   number of random numbers
  lo  minimum value
  hi  maximum value
USAGE;

/**
 * @return never-return
 */
function show_usage(): void
{
    fprintf(STDERR, USAGE);
    exit(1);
}

/**
 * @param string[] $args
 * @return array{int, float, float}
 */
function parse_args(array $args): array
{
    $count = count($args);
    if ($count <= 1) {
        show_usage();
    }

    $n = filter_var($args[1], FILTER_VALIDATE_INT);
    if ($n === false) {
        show_usage();
    }

    if ($count <= 2) {
        return [$n, 0.0, 1.0];
    }

    if ($count !== 4) {
        show_usage();
    }

    $lo = filter_var($args[2], FILTER_VALIDATE_FLOAT);
    if ($lo === false) {
        show_usage();
    }
    $hi = filter_var($args[3], FILTER_VALIDATE_FLOAT);
    if ($hi === false) {
        show_usage();
    }

    return [$n, $lo, $hi];
}

[$n, $lo, $hi] = parse_args($argv);
$random = Random::withEngine(new Mt19937());
$out = Stdio::out();
for ($i = 0; $i < $n; $i++) {
    $x = $random->uniformFloat($lo, $hi);
    $out->printf("%.2f\n", $x);
}