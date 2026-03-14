<?php

declare(strict_types=1);

use App\Presentation\Config\AppConfiguration;
use Psr\Container\ContainerInterface;

return [
    'MYSQL_DSN' => function (ContainerInterface $c): string {
        $config = $c->get(AppConfiguration::class);
        return 'mysql:host=' . $config->mysqlUrl . ';'
            . 'dbname=' . $config->mysqlDatabase;
    },
    PDO::class => function (ContainerInterface $c): PDO {
        $config = $c->get(AppConfiguration::class);
        return new PDO(
            $c->get('MYSQL_DSN'),
            $config->mysqlUser,
            $config->mysqlUserPass,
        );
    },
];
