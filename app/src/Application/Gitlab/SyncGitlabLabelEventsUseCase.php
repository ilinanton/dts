<?php

declare(strict_types=1);

namespace App\Application\Gitlab;

use App\Application\UseCaseInterface;
use App\Domain\Gitlab\ResourceLabelEvent\Repository\GitlabApiResourceLabelEventRepositoryInterface;
use App\Domain\Gitlab\ResourceLabelEvent\Repository\GitlabDataBaseResourceLabelEventRepositoryInterface;

final readonly class SyncGitlabLabelEventsUseCase implements UseCaseInterface
{
    private const COUNT_ITEMS_PER_PAGE = 60;

    public function __construct(
        private GitlabApiResourceLabelEventRepositoryInterface $apiResourceLabelEventRepository,
        private GitlabDataBaseResourceLabelEventRepositoryInterface $databaseResourceLabelEventRepository,
    ) {
    }

    public function execute(): void
    {
//        $page = 0;
//        do {
//            ++$page;
//            $collection = $this->gitlabApiLabelRepository->get([
//                'page' => $page,
//                'per_page' => self::COUNT_ITEMS_PER_PAGE,
//            ]);
//            foreach ($collection as $item) {
//                echo 'Load label #' . $item->id->value . ' ' . $item->name->value;
//                $this->gitlabDataBaseLabelRepository->save($item);
//                echo ' done ' . PHP_EOL;
//            }
//        } while (self::COUNT_ITEMS_PER_PAGE === count($collection));
    }
}
