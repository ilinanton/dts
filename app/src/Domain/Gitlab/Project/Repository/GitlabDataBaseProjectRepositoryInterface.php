<?php

namespace App\Domain\Gitlab\Project\Repository;

use App\Domain\Gitlab\Project\Project;
use App\Domain\Gitlab\Project\ProjectCollection;

interface GitlabDataBaseProjectRepositoryInterface
{
    public function save(Project $object): void;
    public function getAll(): ProjectCollection;
    public function findByUrlToRepo(string $url): ProjectCollection;
}
