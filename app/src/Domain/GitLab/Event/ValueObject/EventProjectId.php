<?php

namespace App\Domain\GitLab\Event\ValueObject;

use App\Domain\GitLab\Common\ValueObject\AbstractRequiredUnsignedInt;

final readonly class EventProjectId extends AbstractRequiredUnsignedInt
{
}
