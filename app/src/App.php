<?php

namespace App;

use Psr\Container\ContainerInterface;

final class App
{
    private ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function run(): void
    {
        echo "H!" . PHP_EOL;
    }
}
