<?php

namespace App\Domain\GitLab\Event\ValueObject;

use InvalidArgumentException;

final class EventActionName
{
    private string $value;

    public function __construct(string $value)
    {
        $this->assertValueIsValid($value);
        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    private function assertValueIsValid(string $value): void
    {
        if (0 === strlen($value)) {
            throw new InvalidArgumentException(get_class($this) . ' is empty!');
        }
    }
}
