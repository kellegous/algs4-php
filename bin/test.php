<?php

declare(strict_types=1);

use Kellegous\Algs4\Graphics\Destination;
use Kellegous\Algs4\Graphics\Drawing;

require __DIR__ . '/../vendor/autoload.php';

$m = new Drawing(800, 600);
$m->floodFill(400, 300, $m->colorFromRGB(0xff, 0xff, 0xff));
$m->ellipse(400, 300, 1, 1)
    ->fill($m->colorFromRGB(0, 0, 0));
$m->writePNG(Destination::toFile('test.png'));

