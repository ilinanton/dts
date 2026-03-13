<?php

declare(strict_types=1);

namespace App\Application\Gitlab;

use App\Application\SyncOutputInterface;
use App\Application\UseCaseInterface;
use App\Domain\Gitlab\Common\SyncDateAfter;
use App\Domain\Gitlab\Event\EventFilterCollection;
use App\Domain\Gitlab\Event\Repository\GitlabApiEventRepositoryInterface;
use App\Domain\Gitlab\Event\Repository\GitlabDataBaseEventRepositoryInterface;
use App\Domain\Gitlab\Project\Project;
use App\Domain\Gitlab\Project\Repository\GitlabDataBaseProjectRepositoryInterface;

final readonly class SyncGitlabProjectEventsUseCase implements UseCaseInterface
{
    private const int COUNT_ITEMS_PER_PAGE = 40;

    public function __construct(
        private SyncDateAfter $syncDateAfter,
        private GitlabDataBaseProjectRepositoryInterface $dataBaseProjectRepository,
        private GitlabApiEventRepositoryInterface $apiEventRepository,
        private GitlabDataBaseEventRepositoryInterface $dataBaseEventRepository,
        private EventFilterCollection $eventFilters,
        private SyncOutputInterface $output,
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
            $this->output->write('   - ' . $filter->paramName . ': ' . $filter->value);
            $page = 0;
            do {
                ++$page;

                $params = [
                    'page' => $page,
                    'per_page' => self::COUNT_ITEMS_PER_PAGE,
                    'after' => $this->syncDateAfter->getValueInMainFormat(),
                    $filter->paramName => $filter->value,
                ];

                $eventCollection = $this->apiEventRepository->getByProjectId($projectId, $params);

                foreach ($eventCollection as $event) {
                    $this->dataBaseEventRepository->save($event);
                }
                $this->output->write(' .');
            } while (self::COUNT_ITEMS_PER_PAGE === count($eventCollection));
            $this->output->writeLine(' done');
        }
    }
}
