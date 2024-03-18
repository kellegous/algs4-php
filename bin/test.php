<?php

declare(strict_types=1);

use Kellegous\Algs4\In;
use Kellegous\Algs4\IOException;

require __DIR__ . '/../vendor/autoload.php';

function stream_with(string $content): mixed
{
    $data = base64_encode($content);
    $stream = fopen("data://text/plain;base64,$data", 'r');
    if ($stream === false) {
        throw new IOException('Unable to create stream');
    }
    return $stream;
}

$in = new In(stream_with("\n"));
var_dump(iterator_to_array($in->readLines()));
