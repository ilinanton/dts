<?php

declare(strict_types=1);

namespace App\Domain\Weeek\Tag\Repository;

use App\Domain\Weeek\Tag\TagCollection;

interface WeeekApiTagRepositoryInterface
{
    public function get(): TagCollection;
}
