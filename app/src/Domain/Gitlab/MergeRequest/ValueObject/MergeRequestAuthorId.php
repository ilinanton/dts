<?php

namespace App\Domain\Gitlab\MergeRequest\ValueObject;

use App\Domain\Common\ValueObject\AbstractRequiredUnsignedInt;

final readonly class MergeRequestAuthorId extends AbstractRequiredUnsignedInt
{
}
