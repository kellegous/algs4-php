<?php

declare(strict_types=1);

use Kellegous\Algs4\Graphics\Destination;
use Kellegous\Algs4\Graphics\Drawing;

require __DIR__ . '/../vendor/autoload.php';

$m = new Drawing(800, 600);
$m->floodFill(400, 300, $m->colorFromRGB(0xff, 0xff, 0xff));
$m->ellipse(400, 300, 200, 150)
    ->fill($m->colorFromRGBA(0, 0, 0, 200));
$m->writePNG(Destination::toFile('test.png'));

$a = $m->colorFromRGB(0xff, 0xff, 0xff);
var_dump($a->alpha());
printf("%x\n", $a->getID());
$b = $m->colorFromRGBA(0xff, 0xff, 0xff, 127);
printf("alpha = %x\n", $b->alpha());
