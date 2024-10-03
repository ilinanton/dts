<?php

use App\Presentation\Cli\Cli;

$container = require __DIR__ . '/src/Presentation/bootstrap.php';

$app = new Cli($container);

echo 'Code: ' . $app->run() . PHP_EOL;
