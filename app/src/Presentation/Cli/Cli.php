<?php

declare(strict_types=1);

namespace App\Presentation\Cli;

use App\Application\UseCaseInterface;
use Psr\Container\ContainerInterface;
use Throwable;

final readonly class Cli
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
                $input = trim(readline('Command id: '));
                $inputCommands = explode(',', $input);
                foreach ($inputCommands as $inputCommand) {
                    echo '--------------------' . PHP_EOL;
                    $this->identifyCommand((int)$inputCommand)->execute();
                }
                readline('Press any key to continue...');
            } catch (Throwable $exception) {
                $code = $exception->getCode();
                echo PHP_EOL . '#' . $code . ' ' . $exception->getMessage() . PHP_EOL;
                echo $exception->getTraceAsString() . PHP_EOL;
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
