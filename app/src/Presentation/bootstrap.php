<?php

declare(strict_types=1);

require __DIR__ . '/../../vendor/autoload.php';

use App\Presentation\Config\AppConfiguration;
use App\Presentation\Config\GitlabConfiguration;
use DI\ContainerBuilder;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

$appConfig = AppConfiguration::fromEnv($_ENV);
$gitlabConfig = GitlabConfiguration::fromEnv($_ENV);

$containerBuilder = new ContainerBuilder();
$containerBuilder->useAttributes(false);
$containerBuilder->useAutowiring(false);
$containerBuilder->addDefinitions([
    AppConfiguration::class => $appConfig,
    GitlabConfiguration::class => $gitlabConfig,
]);
$containerBuilder->addDefinitions(__DIR__ . '/Config/main.php');
$containerBuilder->addDefinitions(__DIR__ . '/Config/cli.php');
$containerBuilder->addDefinitions(__DIR__ . '/Config/gitlab.php');
return $containerBuilder->build();
