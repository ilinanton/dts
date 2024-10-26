<?php

declare(strict_types=1);

namespace App\Domain\Gitlab\Project\ValueObject;

use App\Domain\Common\ValueObject\AbstractRequiredUnsignedInt;

final readonly class ProjectId extends AbstractRequiredUnsignedInt
{
}
