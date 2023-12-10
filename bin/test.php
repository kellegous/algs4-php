<?php

declare(strict_types=1);
require 'vendor/autoload.php';

$unregister = \Kellegous\Algs4\Testing\ErrorStream::register();
try {
    fopen('error-after://foo', 'r');
} finally {
    $unregister();
}