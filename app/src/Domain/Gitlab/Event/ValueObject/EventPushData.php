<?php

declare(strict_types=1);

namespace App\Domain\Gitlab\Event\ValueObject;

use App\Domain\Gitlab\PushData\Factory\PushDataFromArray;
use App\Domain\Gitlab\PushData\PushData;

final readonly class EventPushData
{
    public PushData $value;

    public function __construct(array $value)
    {
        $pushDataFactory = new PushDataFromArray($value);
        $this->value = $pushDataFactory->create();
    }
}
