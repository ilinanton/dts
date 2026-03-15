<?php

declare(strict_types=1);

namespace App\Domain\Gitlab\Event;

enum EventFilterValue: string
{
    case Pushed = 'pushed';
    case Commented = 'commented';
    case MergeRequest = 'merge_request';
}
