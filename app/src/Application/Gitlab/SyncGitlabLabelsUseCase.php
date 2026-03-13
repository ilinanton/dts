<?php

declare(strict_types=1);

namespace App\Application\Gitlab;

use App\Application\SyncOutputInterface;
use App\Application\UseCaseInterface;
use App\Domain\Gitlab\Common\ItemsPerPage;
use App\Domain\Gitlab\Label\Repository\GitlabApiLabelRepositoryInterface;
use App\Domain\Gitlab\Label\Repository\GitlabDataBaseLabelRepositoryInterface;

final readonly class SyncGitlabLabelsUseCase implements UseCaseInterface
{
    public function __construct(
        private GitlabApiLabelRepositoryInterface $apiLabelRepository,
        private GitlabDataBaseLabelRepositoryInterface $dataBaseLabelRepository,
        private SyncOutputInterface $output,
        private ItemsPerPage $itemsPerPage,
    ) {
    }

    public function execute(): void
    {
        $page = 0;
        do {
            ++$page;
            $collection = $this->apiLabelRepository->get([
                'page' => $page,
                'per_page' => $this->itemsPerPage->value,
            ]);
            foreach ($collection as $item) {
                $this->dataBaseLabelRepository->save($item);
                $this->output->writeLine('Load label #' . $item->id->value . ' ' . $item->name->value . ' done');
            }
        } while ($this->itemsPerPage->value === count($collection));
    }
}
