<?php

declare(strict_types=1);

namespace App\Domain\Gitlab\MergeRequest\ValueObject;

use App\Domain\Common\ValueObject\AbstractRequiredString;

final readonly class MergeRequestTitle extends AbstractRequiredString
{
}
