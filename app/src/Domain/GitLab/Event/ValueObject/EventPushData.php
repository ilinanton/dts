<?php

namespace App\Domain\GitLab\Event\ValueObject;

use App\Domain\GitLab\PushData\PushData;
use App\Domain\GitLab\PushData\PushDataFactory;

final class EventPushData
{
    private PushData $value;

    public function __construct(array $value)
    {
        $PushDataFactory = new PushDataFactory();
        $this->value = $PushDataFactory->create($value);
    }

    public function getValue(): PushData
    {
        return $this->value;
    }
}
