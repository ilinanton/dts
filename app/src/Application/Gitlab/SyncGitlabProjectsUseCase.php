<?php

declare(strict_types=1);

namespace App\Application\Gitlab;

use App\Application\SyncOutputInterface;
use App\Application\UseCaseInterface;
use App\Domain\Gitlab\Common\ItemsPerPage;
use App\Domain\Gitlab\Project\Repository\GitlabApiProjectRepositoryInterface;
use App\Domain\Gitlab\Project\Repository\GitlabDataBaseProjectRepositoryInterface;

final readonly class SyncGitlabProjectsUseCase implements UseCaseInterface
{
    public function __construct(
        private GitlabApiProjectRepositoryInterface $apiProjectRepository,
        private GitlabDataBaseProjectRepositoryInterface $dataBaseProjectRepository,
        private SyncOutputInterface $output,
        private ItemsPerPage $itemsPerPage,
    ) {
    }

    public function execute(): void
    {
        $page = 0;
        do {
            ++$page;
            $projectCollection = $this->apiProjectRepository->get([
                'page' => $page,
                'per_page' => $this->itemsPerPage->value,
            ]);
            foreach ($projectCollection as $project) {
                $this->dataBaseProjectRepository->save($project);
                $this->output->writeLine('Load project #' . $project->id->value . ' ' . $project->name->value . ' done');
            }
        } while ($this->itemsPerPage->value === count($projectCollection));
    }
}
