<?php

namespace App\Domain\GitLab\Project\Repository;

interface GitLabApiProjectRepositoryInterface
{
    public function get(): array;
}