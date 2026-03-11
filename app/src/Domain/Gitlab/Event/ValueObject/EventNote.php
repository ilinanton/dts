<?php

declare(strict_types=1);

namespace App\Domain\Gitlab\Event\ValueObject;

use App\Domain\Gitlab\Note\Note;

final readonly class EventNote
{
    public function __construct(
        public Note $value,
    ) {
    }
}
