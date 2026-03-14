<?php

declare(strict_types=1);

namespace App\Application\Gitlab;

use App\Application\Common\Paginator;
use App\Application\SyncOutputInterface;
use App\Application\UseCaseInterface;
use App\Domain\Gitlab\Project\ProjectCollection;
use App\Domain\Gitlab\Project\Repository\GitlabApiProjectRepositoryInterface;
use App\Domain\Gitlab\Project\Repository\GitlabDataBaseProjectRepositoryInterface;

final readonly class SyncGitlabProjectsUseCase implements UseCaseInterface
{
    public function __construct(
        private GitlabApiProjectRepositoryInterface $apiProjectRepository,
        private GitlabDataBaseProjectRepositoryInterface $dataBaseProjectRepository,
        private SyncOutputInterface $output,
        private Paginator $paginator,
    ) {
    }

    public function execute(): void
    {
        $items = $this->paginator->paginate(
            fn(array $params): ProjectCollection => $this->apiProjectRepository->get($params),
        );
        foreach ($items as $project) {
            $this->dataBaseProjectRepository->save($project);
            $this->output->writeLine('Load project #' . $project->id->value . ' ' . $project->name->value . ' done');
        }
    }
}
