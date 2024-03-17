<?php

namespace App;

use App\Application\Command;
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
            try {
                $this->identifyCommand(Command::menu->id())->execute();
                $input = (int) readline("Command id: ");
                $this->identifyCommand($input)->execute();
            } catch (\Throwable $exception) {
                echo $exception->getMessage() . PHP_EOL;
                return $exception->getCode();
            }
        }
    }

    private function identifyCommand(int $input): UseCaseInterface
    {
        $command = Command::getByIndex($input);
        return $this->container->get($command->diId());
    }
}
