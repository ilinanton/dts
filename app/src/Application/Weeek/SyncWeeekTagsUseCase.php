<?php

declare(strict_types=1);

namespace App\Application\Weeek;

use App\Application\UseCaseInterface;
use App\Domain\Weeek\Tag\Repository\WeeekApiTagRepositoryInterface;
use App\Domain\Weeek\Tag\Repository\WeeekDataBaseTagRepositoryInterface;

final readonly class SyncWeeekTagsUseCase implements UseCaseInterface
{
    public function __construct(
        private WeeekApiTagRepositoryInterface $apiTagRepository,
        private WeeekDataBaseTagRepositoryInterface $dataBaseTagRepository,
    ) {
    }

    public function execute(): void
    {
        $collection = $this->apiTagRepository->get();
        foreach ($collection as $item) {
            echo 'Load tag #' . $item->id->value . ' ' . $item->title->value;
            $this->dataBaseTagRepository->save($item);
            echo ' done ' . PHP_EOL;
        }
    }
}
