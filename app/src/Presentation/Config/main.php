<?php

declare(strict_types=1);

use Psr\Container\ContainerInterface;

return [
    'MYSQL_URL' => $_ENV['MYSQL_URL'],
    'MYSQL_DATABASE' => $_ENV['MYSQL_DATABASE'],
    'MYSQL_USER' => $_ENV['MYSQL_USER'],
    'MYSQL_USER_PASS' => $_ENV['MYSQL_USER_PASS'],
    'MYSQL_DSN' => function (ContainerInterface $c): string {
        return 'mysql:host=' . $c->get('MYSQL_URL') . ';'
            . 'dbname=' . $c->get('MYSQL_DATABASE');
    },
    PDO::class => function (ContainerInterface $c): PDO {
        return new PDO(
            $c->get('MYSQL_DSN'),
            $c->get('MYSQL_USER'),
            $c->get('MYSQL_USER_PASS')
        );
    },
];
