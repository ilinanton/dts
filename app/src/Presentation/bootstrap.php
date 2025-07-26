<?php

declare(strict_types=1);

require __DIR__ . '/../../vendor/autoload.php';

use DI\ContainerBuilder;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

$containerBuilder = new ContainerBuilder();
$containerBuilder->useAttributes(false);
$containerBuilder->useAutowiring(false);
$containerBuilder->addDefinitions(__DIR__ . '/config.php');
return $containerBuilder->build();
