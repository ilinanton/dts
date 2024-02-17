<?php

namespace App\Domain\GitLab\Common\Repository;

interface ApiInterface
{
    public function getGroupMembers(): array;

    public function getGroupProjects(): array;
}
