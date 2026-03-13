<?php

declare(strict_types=1);

namespace App\Domain\Gitlab\Event;

final readonly class EventFilter
{
    public function __construct(
        public string $paramName,
        public string $value,
    ) {
    }
}
