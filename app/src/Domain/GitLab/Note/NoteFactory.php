<?php

namespace App\Domain\GitLab\Note;

use App\Domain\GitLab\Note\ValueObject\NoteBody;

final class NoteFactory
{
    public function create(array $data): Note
    {
        return new Note(
            new NoteBody($data['body'] ?? ''),
        );
    }
}
