<?php

declare(strict_types=1);

namespace App\Domain\Gitlab\User\ValueObject;

use InvalidArgumentException;

final readonly class UserState
{
    private const string STATE_ACTIVE = 'active';
    private const string STATE_BLOCKED = 'blocked';
    private const string STATE_DEACTIVATED = 'deactivated';

    private const array STATE_LIST = [
        self::STATE_ACTIVE,
        self::STATE_BLOCKED,
        self::STATE_DEACTIVATED,
    ];

    public string $value;

    public function __construct(string $value)
    {
        $this->assertValueIsValid($value);
        $this->value = $value;
    }

    private function assertValueIsValid(string $value): void
    {
        if (!in_array($value, self::STATE_LIST, true)) {
            throw new InvalidArgumentException('User state is incorrect!');
        }
    }
}
