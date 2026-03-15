<?php

declare(strict_types=1);

namespace App\Application\Gitlab;

use App\Application\Common\Paginator;
use App\Application\SyncOutputInterface;
use App\Application\UseCaseInterface;
use App\Domain\Gitlab\Common\SyncDateAfter;
use App\Domain\Gitlab\Event\EventCollection;
use App\Domain\Gitlab\Event\EventFilterCollection;
use App\Domain\Gitlab\Event\Repository\GitlabSourceEventRepositoryInterface;
use App\Domain\Gitlab\Event\Repository\GitlabStorageEventRepositoryInterface;
use App\Domain\Gitlab\Project\Project;
use App\Domain\Gitlab\Project\Repository\GitlabStorageProjectRepositoryInterface;

final readonly class SyncGitlabProjectEventsUseCase implements UseCaseInterface
{
    public function __construct(
        private SyncDateAfter $syncDateAfter,
        private GitlabStorageProjectRepositoryInterface $dataBaseProjectRepository,
        private GitlabSourceEventRepositoryInterface $apiEventRepository,
        private GitlabStorageEventRepositoryInterface $dataBaseEventRepository,
        private EventFilterCollection $eventFilters,
        private SyncOutputInterface $output,
        private Paginator $paginator,
    ) {
    }

    public function execute(): void
    {
        $this->output->writeLine('Load project events that happened after ' . $this->syncDateAfter->getValueInMainFormat());
        $projectCollection = $this->dataBaseProjectRepository->getAll();
        foreach ($projectCollection as $project) {
            $this->syncProject($project);
        }
    }

    private function syncProject(Project $project): void
    {
        $projectId = $project->id->value;
        $projectName = $project->name->value;
        $this->output->writeLine(' - #' . $projectId . ' ' . $projectName);
        foreach ($this->eventFilters as $filter) {
            $this->output->write('   - ' . $filter->paramName->value . ': ' . $filter->value->value);

            $baseParams = [
                'after' => $this->syncDateAfter->getValueInMainFormat(),
                $filter->paramName->value => $filter->value->value,
            ];

            $items = $this->paginator->paginate(
                function (array $params) use ($projectId): EventCollection {
                    $this->output->write(' .');
                    return $this->apiEventRepository->getByProjectId($projectId, $params);
                },
                $baseParams,
            );

            foreach ($items as $event) {
                $this->dataBaseEventRepository->save($event);
            }
            $this->output->writeLine(' done');
        }
    }
}
