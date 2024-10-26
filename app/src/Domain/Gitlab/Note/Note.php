<?php

declare(strict_types=1);

namespace App\Domain\Gitlab\Note;

use App\Domain\Gitlab\Note\ValueObject\NoteBody;

final readonly class Note
{
    public function __construct(
        public NoteBody $body,
    ) {
    }
}
