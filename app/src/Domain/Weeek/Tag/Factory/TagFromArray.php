<?php

declare(strict_types=1);

namespace App\Domain\Weeek\Tag\Factory;

use App\Domain\Weeek\Tag\Tag;
use App\Domain\Weeek\Tag\ValueObject\TagColor;
use App\Domain\Weeek\Tag\ValueObject\TagId;
use App\Domain\Weeek\Tag\ValueObject\TagTitle;

final readonly class TagFromArray
{
    public function __construct(
        private array $data,
    ) {
    }

    public function create(): Tag
    {
        return new Tag(
            new TagId($this->data['id'] ?? 0),
            new TagTitle($this->data['title'] ?? ''),
            new TagColor($this->data['color'] ?? ''),
        );
    }
}
