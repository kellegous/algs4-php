<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

$data = [20 => 3, 12 => 4];
$data = array_map(
    function (int $v, int $k): int {
        printf("k = %d, v = %d\n", $k, $v);
        return $v + $k;
    },
    $data,
    $data,
);
var_dump($data);
