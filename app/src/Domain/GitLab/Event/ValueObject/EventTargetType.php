<?php

namespace App\Domain\GitLab\Event\ValueObject;

final class EventTargetType
{
    private string $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
