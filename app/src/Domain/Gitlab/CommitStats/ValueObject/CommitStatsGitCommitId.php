<?php

declare(strict_types=1);

namespace App\Domain\Gitlab\CommitStats\ValueObject;

use App\Domain\Common\ValueObject\AbstractRequiredString;

final readonly class CommitStatsGitCommitId extends AbstractRequiredString
{
}
