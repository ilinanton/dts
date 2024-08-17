<?php

namespace App\Domain\GitLab\MergeRequest\ValueObject;

use App\Domain\GitLab\Common\ValueObject\AbstractRequiredUnsignedInt;

final readonly class MergeRequestId extends AbstractRequiredUnsignedInt
{
}
