<?php

namespace App\Domain\GitLab\Event\ValueObject;

use App\Domain\GitLab\PushData\PushData;
use App\Domain\GitLab\PushData\PushDataFactory;

final class EventPushData
{
    private PushData $value;

    public function __construct(array $value)
    {
        $pushDataFactory = new PushDataFactory();
        $this->value = $pushDataFactory->create($value);
    }

    public function getValue(): PushData
    {
        return $this->value;
    }
}
