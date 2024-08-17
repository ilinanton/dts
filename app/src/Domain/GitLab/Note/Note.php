<?php

namespace App\Domain\GitLab\Note;

use App\Domain\GitLab\Note\ValueObject\NoteBody;

final readonly class Note
{
    public function __construct(
        private NoteBody $body,
    ) {
    }

    public function getBody(): NoteBody
    {
        return $this->body;
    }
}
