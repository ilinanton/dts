<?php

namespace App\Domain\GitLab\Project\ValueObject;

use App\Domain\GitLab\Common\ValueObject\AbstractRequiredUnsignedInt;

final readonly class ProjectId extends AbstractRequiredUnsignedInt
{
}
