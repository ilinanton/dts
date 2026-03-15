<?php

declare(strict_types=1);

namespace App\Infrastructure\Gitlab\Factory;

use App\Domain\Gitlab\Note\Note;
use App\Domain\Gitlab\Note\ValueObject\NoteBody;

final class NoteFactory
{
    /** @param array{body?: string} $data */
    public function create(array $data): Note
    {
        return new Note(
            new NoteBody($data['body'] ?? ''),
        );
    }
}
