<?php

namespace App;

use App\Application\UseCaseInterface;
use Psr\Container\ContainerInterface;

final class App
{
    private ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function run(): int
    {
        while (true) {
            $command = readline("Command: ");
            try {
                $this->identifyCommand($command)->execute();
            } catch (\Throwable $exception) {
                echo $exception->getMessage() . PHP_EOL;
                return $exception->getCode();
            }
        }
    }

    private function identifyCommand(string $command): UseCaseInterface
    {
        $commandId = 'command_' . $command;
        if (!$this->container->has($commandId)) {
            throw new \Exception('Command not found!');
        }
        return $this->container->get($commandId);
    }
}
