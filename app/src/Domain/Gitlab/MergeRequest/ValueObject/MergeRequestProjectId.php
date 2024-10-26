<?php

declare(strict_types=1);

namespace App\Domain\Gitlab\MergeRequest\ValueObject;

use App\Domain\Common\ValueObject\AbstractUnsignedInt;

final readonly class MergeRequestProjectId extends AbstractUnsignedInt
{
}
