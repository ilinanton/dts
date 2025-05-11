<?php

declare(strict_types=1);

namespace App\Domain\Gitlab\ResourceLabelEvent\ValueObject;

use App\Domain\Common\ValueObject\AbstractUnsignedInt;

final readonly class ResourceLabelEventProjectId extends AbstractUnsignedInt
{
}
