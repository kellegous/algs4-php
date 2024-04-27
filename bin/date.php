<?php

declare(strict_types=1);

use Kellegous\Algs4\Date;
use Kellegous\Algs4\Date\Month;
use Kellegous\Algs4\Stdio;

/**
 * Execution: php bin/date.php
 */

require __DIR__ . '/../vendor/autoload.php';

$today = Date::fromYMD(2004, Month::February, 25);
Stdio::out()->println($today);
for ($i = 0; $i < 10; $i++) {
    $today = $today->next();
    Stdio::out()->println($today);
}

Stdio::out()->println(
    $today->isAfter($today->next()) ? 'true' : 'false'
);
Stdio::out()->println(
    $today->isAfter($today) ? 'true' : 'false'
);
Stdio::out()->println(
    $today->next()->isAfter($today) ? 'true' : 'false'
);

$birthday = Date::fromYMD(1971, Month::October, 16);
Stdio::out()->println($birthday);
for ($i = 0; $i < 10; $i++) {
    $birthday = $birthday->next();
    Stdio::out()->println($birthday);
}