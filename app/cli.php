<?php

declare(strict_types=1);

use App\Presentation\Cli\Cli;

$container = require __DIR__ . '/src/Presentation/bootstrap.php';

$app = new Cli($container);

echo 'Code: ' . $app->run() . PHP_EOL;
