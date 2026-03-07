<?php

declare(strict_types=1);

namespace App\Domain\Gitlab\MergeRequest\ValueObject;

enum MergeRequestState: string
{
    case Opened = 'opened';
    case Closed = 'closed';
    case Locked = 'locked';
    case Merged = 'merged';
}
