<?php

declare(strict_types=1);

/******************************************************************************
 *  Execution:    php bin/cat.php input0.txt input1.txt ... output.txt
 *  Data files:   https://algs4.cs.princeton.edu/11model/in1.txt
 *                https://algs4.cs.princeton.edu/11model/in2.txt
 *
 *  Reads in text files specified as the first command-line
 *  arguments, concatenates them, and writes the result to
 *  filename specified as the last command-line arguments.
 *
 *  % more in1.txt
 *  This is
 *
 *  % more in2.txt
 *  a tiny
 *  test.
 *
 *  % php bin/cat.php in1.txt in2.txt out.txt
 *
 *  % more out.txt
 *  This is
 *  a tiny
 *  test.
 *
 ******************************************************************************/

use Kellegous\Algs4\In;
use Kellegous\Algs4\Out;

require __DIR__ . '././vendor/autoload.php';

if ($argc < 3) {
    echo "Usage: php bin/cat.php <input> ... <output>\n";
    exit(1);
}

$out = Out::toFile($argv[$argc - 1]);
for ($i = 1; $i < $argc - 1; $i++) {
    $in = In::fromFile($argv[$i]);
    try {
        $out->println($in->readAll());
    } finally {
        $in->close();
    }
}