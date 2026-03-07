<?php

declare(strict_types=1);

namespace App\Domain\Gitlab\Project;

use App\Domain\Common\AbstractCollection;

/** @extends AbstractCollection<Project> */
final class ProjectCollection extends AbstractCollection
{
    protected function getType(): string
    {
        return Project::class;
    }
}
