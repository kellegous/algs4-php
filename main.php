<?php

use Kellegous\LinkedList\Node;

require __DIR__ . '/vendor/autoload.php';

$list = Node::fromArray([1, 2, 3])
    ->concat(Node::fromArray([4, 5, 6]));

foreach ($list->iterate() as $value) {
    printf("%s\n", $value);
}