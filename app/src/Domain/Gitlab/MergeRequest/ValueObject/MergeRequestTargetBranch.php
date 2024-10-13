<?php

namespace App\Domain\Gitlab\MergeRequest\ValueObject;

use App\Domain\Common\ValueObject\AbstractRequiredString;

final readonly class MergeRequestTargetBranch extends AbstractRequiredString
{
}
