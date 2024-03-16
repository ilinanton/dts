<?php

namespace App;

use App\Interface\Console\Command;
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
                $this->container->get($this->identifyCommand($command)->useCaseClass())->execute();
            } catch (\Throwable $exception) {
                echo $exception->getMessage() . PHP_EOL;
                return $exception->getCode();
            }
        }
    }

    private function identifyCommand(string $command): Command
    {
        $commandList = Command::cases();
        $matchingCommandIndex = array_search($command, array_column($commandList, "name"));
        if (false === $matchingCommandIndex) {
            throw new \Exception('Command not found!');
        }
        return $commandList[$matchingCommandIndex];
    }
}
