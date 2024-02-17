<?php

use App\App;

$container = require __DIR__ . '/src/bootstrap.php';

$app = new App($container);

$app->run();
