<?php

namespace App\Domain\GitLab\User\ValueObject;

use InvalidArgumentException;

final class UserAvatarUrl
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
        if (false === filter_var($value, FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED)) {
            throw new InvalidArgumentException('Avatar url is incorrect!');
        }
    }
}
