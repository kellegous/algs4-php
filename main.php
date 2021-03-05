<?php

use Kellegous\LinkedList;
use Kellegous\LinkedList\Node;

require __DIR__ . '/vendor/autoload.php';

$list = (new LinkedList())
    ->pushBack(20)
    ->pushBack(30)
    ->pushFront(10);

foreach ($list->iterate() as $value) {
    printf("%s\n", $value);
}