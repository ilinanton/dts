<?php

namespace App\Domain\Gitlab\Event\ValueObject;

use App\Domain\Gitlab\PushData\PushData;
use App\Domain\Gitlab\PushData\PushDataFactory;

final readonly class EventPushData
{
    public PushData $value;

    public function __construct(array $value)
    {
        $pushDataFactory = new PushDataFactory();
        $this->value = $pushDataFactory->create($value);
    }
}
