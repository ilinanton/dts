<?php

namespace App\Domain\Gitlab\Event\ValueObject;

use App\Domain\Gitlab\Note\Note;
use App\Domain\Gitlab\Note\NoteFactory;

final class EventNote
{
    private Note $value;

    public function __construct(array $value)
    {
        $noteFactory = new NoteFactory();
        $this->value = $noteFactory->create($value);
    }

    public function getValue(): Note
    {
        return $this->value;
    }
}
