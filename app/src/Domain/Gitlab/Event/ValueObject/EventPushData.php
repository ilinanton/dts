<?php

declare(strict_types=1);

namespace App\Domain\Gitlab\Event\ValueObject;

use App\Domain\Gitlab\PushData\PushData;

final readonly class EventPushData
{
    public function __construct(
        public PushData $value,
    ) {
    }
}
