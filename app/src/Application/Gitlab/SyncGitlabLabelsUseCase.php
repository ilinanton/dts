<?php

declare(strict_types=1);

namespace App\Application\Gitlab;

use App\Application\UseCaseInterface;
use App\Domain\Gitlab\Label\Repository\GitlabApiLabelRepositoryInterface;
use App\Domain\Gitlab\Label\Repository\GitlabDataBaseLabelRepositoryInterface;

final readonly class SyncGitlabLabelsUseCase implements UseCaseInterface
{
    private const COUNT_ITEMS_PER_PAGE = 60;

    public function __construct(
        private GitlabApiLabelRepositoryInterface $gitlabApiLabelRepository,
        private GitlabDataBaseLabelRepositoryInterface $gitlabDataBaseLabelRepository,
    ) {
    }

    public function execute(): void
    {
        $page = 0;
        do {
            ++$page;
            $collection = $this->gitlabApiLabelRepository->get([
                'page' => $page,
                'per_page' => self::COUNT_ITEMS_PER_PAGE,
            ]);
            foreach ($collection as $item) {
                echo 'Load label #' . $item->id->value . ' ' . $item->name->value;
                $this->gitlabDataBaseLabelRepository->save($item);
                echo ' done ' . PHP_EOL;
            }
        } while (self::COUNT_ITEMS_PER_PAGE === count($collection));
    }
}
