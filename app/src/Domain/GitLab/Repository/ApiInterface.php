<?php

namespace App\Domain\GitLab\Repository;

interface ApiInterface
{
    public function getGroupMembers(): array;

    public function getGroupProjects(): array;
}
