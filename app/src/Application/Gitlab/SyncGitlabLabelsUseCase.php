<?php

declare(strict_types=1);

namespace App\Application\Gitlab;

use App\Application\Common\Paginator;
use App\Application\SyncOutputInterface;
use App\Application\UseCaseInterface;
use App\Domain\Gitlab\Label\LabelCollection;
use App\Domain\Gitlab\Label\Repository\GitlabSourceLabelRepositoryInterface;
use App\Domain\Gitlab\Label\Repository\GitlabStorageLabelRepositoryInterface;

final readonly class SyncGitlabLabelsUseCase implements UseCaseInterface
{
    public function __construct(
        private GitlabSourceLabelRepositoryInterface $apiLabelRepository,
        private GitlabStorageLabelRepositoryInterface $dataBaseLabelRepository,
        private SyncOutputInterface $output,
        private Paginator $paginator,
    ) {
    }

    public function execute(): void
    {
        $items = $this->paginator->paginate(
            fn(array $params): LabelCollection => $this->apiLabelRepository->get($params),
        );
        foreach ($items as $item) {
            $this->dataBaseLabelRepository->save($item);
            $this->output->writeLine('Load label #' . $item->id->value . ' ' . $item->name->value . ' done');
        }
    }
}
