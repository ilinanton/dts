<?php

declare(strict_types=1);

namespace App\Application\Weeek;

use App\Application\UseCaseInterface;
use App\Domain\Weeek\User\Repository\WeeekApiUserRepositoryInterface;
use App\Domain\Weeek\User\Repository\WeeekDataBaseUserRepositoryInterface;

final readonly class SyncWeeekUsersUseCase implements UseCaseInterface
{
    public function __construct(
        private WeeekApiUserRepositoryInterface $apiUserRepository,
        private WeeekDataBaseUserRepositoryInterface $dataBaseUserRepository,
    ) {
    }

    public function execute(): void
    {
        $collection = $this->apiUserRepository->get();
        foreach ($collection as $item) {
            echo 'Load user #' . $item->id->value . ' ' . $item->email->value;
            $this->dataBaseUserRepository->save($item);
            echo ' done ' . PHP_EOL;
        }
    }
}
