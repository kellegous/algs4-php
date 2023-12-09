<?php declare(strict_types=1);
require 'vendor/autoload.php';

use Kellegous\Algs4\Stdin;

/**
 *  average.php provides a program for reading in a sequence
 *  of real numbers and printing out their average.
 *  <p>
 *  For additional documentation, see <a href="https://algs4.cs.princeton.edu/11model">Section 1.1</a> of
 *  <i>Algorithms, 4th Edition</i> by Robert Sedgewick and Kevin Wayne.
 * </p>
 *
 * @author Robert Sedgewick
 * @author Kevin Wayne
 * @author Kelly Norton
 */

$sum = 0;
$count = 0;
foreach (Stdin::readFloats() as $i) {
    $sum += $i;
    $count++;
}
$average = $sum / $count;
printf("Average is %s\n", $average);
