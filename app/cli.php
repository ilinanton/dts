<?php

declare(strict_types=1);

use App\Presentation\Cli\Cli;

define('DIR_ROOT', dirname(__FILE__));

$container = require DIR_ROOT . '/src/Presentation/bootstrap.php';

$app = new Cli($container);

echo 'Code: ' . $app->run() . PHP_EOL;
