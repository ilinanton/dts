<?php

namespace App\Domain\Gitlab\Note;

use App\Domain\Gitlab\Note\ValueObject\NoteBody;

final class NoteFactory
{
    public function create(array $data): Note
    {
        return new Note(
            new NoteBody($data['body'] ?? ''),
        );
    }
}
