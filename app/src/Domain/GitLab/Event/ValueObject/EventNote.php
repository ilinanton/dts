<?php

namespace App\Domain\GitLab\Event\ValueObject;

use App\Domain\GitLab\Note\Note;
use App\Domain\GitLab\Note\NoteFactory;

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
