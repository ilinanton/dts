<?php

declare(strict_types=1);

namespace App\Domain\Gitlab\Event\ValueObject;

use App\Domain\Gitlab\Note\Note;
use App\Domain\Gitlab\Note\NoteFactory;

final readonly class EventNote
{
    public Note $value;

    public function __construct(array $value)
    {
        $noteFactory = new NoteFactory();
        $this->value = $noteFactory->create($value);
    }
}
