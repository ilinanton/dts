<?php

namespace App\Domain\GitLab\Event\ValueObject;

final class EventNote
{
    private array $value;

    public function __construct(array $value)
    {
        $this->value = $value;
    }

    public function getValue(): array
    {
        return $this->value;
    }

    public function getJsonValue(): string
    {
        return json_encode($this->value, JSON_THROW_ON_ERROR);
    }
}
