<?php

declare(strict_types=1);

namespace App\Domain\Gitlab\ResourceEvent\ValueObject;

use App\Domain\Common\ValueObject\AbstractRequiredString;

final readonly class ResourceEventResourceType extends AbstractRequiredString
{
}
