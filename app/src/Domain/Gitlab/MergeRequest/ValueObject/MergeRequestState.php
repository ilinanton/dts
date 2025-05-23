<?php

declare(strict_types=1);

namespace App\Domain\Gitlab\MergeRequest\ValueObject;

use InvalidArgumentException;

final readonly class MergeRequestState
{
    private const STATE_OPENED = 'opened';
    private const STATE_CLOSED = 'closed';
    private const STATE_LOCKED = 'locked';
    private const STATE_MERGED = 'merged';

    private const STATE_LIST = [
        self::STATE_OPENED,
        self::STATE_CLOSED,
        self::STATE_LOCKED,
        self::STATE_MERGED,
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
            throw new InvalidArgumentException('State is incorrect!');
        }
    }
}
