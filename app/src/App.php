<?php

namespace App;

use App\Application\Command;
use App\Application\UseCaseInterface;
use Psr\Container\ContainerInterface;
use Throwable;

final readonly class App
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
            } catch (Throwable $exception) {
                $code = $exception->getCode();
                echo '#' . $code . ' ' . $exception->getMessage() . PHP_EOL;
                return is_int($code) ? $code : 1;
            }
        }
    }

    private function identifyCommand(int $input): UseCaseInterface
    {
        $command = Command::getByIndex($input);
        return $this->container->get($command->diId());
    }
}
