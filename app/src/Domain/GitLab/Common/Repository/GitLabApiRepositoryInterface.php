<?php

namespace App\Domain\GitLab\Common\Repository;

interface GitLabApiRepositoryInterface
{
    public function getGroupMembers(): array;

    public function getGroupProjects(): array;
}
