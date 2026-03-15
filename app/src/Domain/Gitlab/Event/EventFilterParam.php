<?php

declare(strict_types=1);

namespace App\Domain\Gitlab\Event;

enum EventFilterParam: string
{
    case Action = 'action';
    case TargetType = 'target_type';
}
