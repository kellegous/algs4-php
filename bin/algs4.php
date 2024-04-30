<?php

declare(strict_types=1);

namespace Kellegous\Algs4;

use Symfony\Component\Console\Application;

require __DIR__ . '/../vendor/autoload.php';

$app = new Application('algs4');
$app->addCommands([
    new Commands\AllowList()
]);
$app->run();