<?php

declare(strict_types=1);

namespace App\Domain\Weeek\Tag\ValueObject;

use App\Domain\Common\ValueObject\AbstractRequiredUnsignedInt;

final readonly class TagId extends AbstractRequiredUnsignedInt
{
}
