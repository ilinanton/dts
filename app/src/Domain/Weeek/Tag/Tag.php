<?php

declare(strict_types=1);

namespace App\Domain\Weeek\Tag;

use App\Domain\Weeek\Tag\ValueObject\TagColor;
use App\Domain\Weeek\Tag\ValueObject\TagId;
use App\Domain\Weeek\Tag\ValueObject\TagTitle;

final readonly class Tag
{
    public function __construct(
        public TagId $id,
        public TagTitle $title,
        public TagColor $color,
    ) {
    }
}
