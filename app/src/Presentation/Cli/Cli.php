<?php

declare(strict_types=1);

namespace App\Presentation\Cli;

use App\Application\SyncOutputInterface;
use App\Application\UseCaseInterface;
use Psr\Container\ContainerInterface;
use Throwable;

final readonly class Cli
{
    public function __construct(
        private ContainerInterface $container,
        private SyncOutputInterface $output,
    ) {
    }

    public function run(): int
    {
        while (true) {
            try {
                $this->identifyCommand(Command::menu->id())->execute();
                $input = trim(readline('Command id: '));
                $inputCommands = explode(',', $input);
                foreach ($inputCommands as $inputCommand) {
                    $this->output->writeLine('--------------------');
                    $this->identifyCommand((int)$inputCommand)->execute();
                }
                readline('Press any key to continue...');
            } catch (Throwable $exception) {
                $code = $exception->getCode();
                $this->output->writeLine(PHP_EOL . '#' . $code . ' ' . $exception->getMessage());
                $this->output->writeLine($exception->getTraceAsString());
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
