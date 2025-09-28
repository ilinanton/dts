<?php

declare(strict_types=1);

namespace App\Domain\Weeek\Tag\Repository;

use App\Domain\Weeek\Tag\Tag;
use App\Domain\Weeek\Tag\TagCollection;

interface WeeekDataBaseTagRepositoryInterface
{
    public function save(Tag $object): void;

    public function getAll(): TagCollection;
}
