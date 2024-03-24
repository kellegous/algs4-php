<?php

declare(strict_types=1);

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
