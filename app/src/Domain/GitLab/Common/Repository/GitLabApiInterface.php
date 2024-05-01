<?php

namespace App\Domain\GitLab\Common\Repository;

interface GitLabApiInterface
{
    public function getGroupMembers(): array;

    public function getGroupProjects(): array;
}
