<?php declare(strict_types=1);

namespace Kellegous\Algs4;

class Main
{
    public static function run(): void
    {
        $array = new \SplFixedArray(2);
        $array[0] = 100;
        $array[1] = 200;
        printf("%s\n", json_encode($array->toArray()));
        printf("%s, %s\n", $array[0], $array[1]);
    }
}