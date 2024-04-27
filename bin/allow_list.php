<?php

declare(strict_types=1);

/******************************************************************************
 *  Execution:    java Allowlist allowlist.txt < data.txt
 *  Execution:    php bin/allow_list.php allowList.txt < data.txt
 *
 *  Data files:   https://algs4.cs.princeton.edu/11model/tinyAllowlist.txt
 *                https://algs4.cs.princeton.edu/11model/tinyText.txt
 *                https://algs4.cs.princeton.edu/11model/largeAllowlist.txt
 *                https://algs4.cs.princeton.edu/11model/largeText.txt
 *
 *  Allowlist filter.
 *
 *  % php bin/allow_list.php tinyAllowlist.txt < tinyText.txt
 *  50
 *  99
 *  13
 *
 *  % php bin/allow_list.php largeAllowList.txt < largeText.txt | more
 *  499569
 *  984875
 *  295754
 *  207807
 *  140925
 *  161828
 *  [367,966 total values]
 *
 ******************************************************************************/

use Kellegous\Algs4\In;
use Kellegous\Algs4\StaticSetOfInts;
use Kellegous\Algs4\Stdio;

require __DIR__ . '/../vendor/autoload.php';

const USAGE = <<<USAGE
Usage: allow_list.php [file]
  file  path to the allow list file
USAGE;

if ($argc !== 2) {
    fprintf(STDERR, USAGE);
    exit(1);
}

$white = new StaticSetOfInts(
    iterator_to_array(In::fromFile($argv[1])->readInts())
);

$stdout = Stdio::out();
foreach (Stdio::in()->readInts() as $i) {
    if (!$white->contains($i)) {
        $stdout->printf("%d\n", $i);
    }
}
