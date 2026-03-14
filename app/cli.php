<?php

declare(strict_types=1);

use App\Application\SyncOutputInterface;
use App\Presentation\Cli\Cli;

$container = require __DIR__ . '/src/Presentation/bootstrap.php';

$app = new Cli($container, $container->get(SyncOutputInterface::class));

echo 'Code: ' . $app->run() . PHP_EOL;
