<?php

use App\App;

$container = require __DIR__ . '/src/bootstrap.php';

$app = new App($container);

echo 'Code: ' . $app->run() . PHP_EOL;
