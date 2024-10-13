<?php

namespace App\Domain\Gitlab\Event\ValueObject;

final readonly class EventTargetTitle
{
    public string $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }
}
