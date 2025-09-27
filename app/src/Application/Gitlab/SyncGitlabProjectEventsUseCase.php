<?php

declare(strict_types=1);

namespace App\Application\Gitlab;

use App\Application\UseCaseInterface;
use App\Domain\Gitlab\Event\Repository\GitlabApiEventRepositoryInterface;
use App\Domain\Gitlab\Event\Repository\GitlabDataBaseEventRepositoryInterface;
use App\Domain\Gitlab\Project\Project;
use App\Domain\Gitlab\Project\Repository\GitlabDataBaseProjectRepositoryInterface;

final readonly class SyncGitlabProjectEventsUseCase implements UseCaseInterface
{
    private const int COUNT_ITEMS_PER_PAGE = 40;
    private const array FILTERS = [
        [
            'param_name' => 'action',
            'value' => 'pushed',
        ],
        [
            'param_name' => 'action',
            'value' => 'commented',
        ],
        [
            'param_name' => 'target_type',
            'value' => 'merge_request',
        ],
    ];

    public function __construct(
        private string $syncDateAfter,
        private GitlabDataBaseProjectRepositoryInterface $dataBaseProjectRepository,
        private GitlabApiEventRepositoryInterface $apiEventRepository,
        private GitlabDataBaseEventRepositoryInterface $dataBaseEventRepository,
    ) {
    }

    public function execute(): void
    {
        echo 'Load project events that happened after ' . $this->syncDateAfter . PHP_EOL;
        $projectCollection = $this->dataBaseProjectRepository->getAll();
        foreach ($projectCollection as $project) {
            $this->syncProject($project);
        }
    }

    private function syncProject(Project $project): void
    {
        $projectId = $project->id->value;
        $projectName = $project->name->value;
        echo ' - #' . $projectId . ' ' . $projectName . PHP_EOL;
        foreach (self::FILTERS as $filter) {
            echo '   - ' . $filter['param_name'] . ': ' . $filter['value'];
            $page = 0;
            do {
                ++$page;

                $params = [
                    'page' => $page,
                    'per_page' => self::COUNT_ITEMS_PER_PAGE,
                    'after' => $this->syncDateAfter,
                    $filter['param_name'] => $filter['value'],
                ];

                $eventCollection = $this->apiEventRepository->getByProjectId($projectId, $params);

                foreach ($eventCollection as $event) {
                    $this->dataBaseEventRepository->save($event);
                }
                echo ' .';
            } while (self::COUNT_ITEMS_PER_PAGE === count($eventCollection));
            echo ' done ' . PHP_EOL;
        }
    }
}
