<?php

require __DIR__ . '/../vendor/autoload.php';

use DI\ContainerBuilder;

$containerBuilder = new ContainerBuilder();
$containerBuilder->useAttributes(false);
$containerBuilder->useAutowiring(false);
$containerBuilder->addDefinitions('config.php');
return $containerBuilder->build();
