<?php

declare(strict_types=1);

namespace App\Domain\Gitlab\Event\ValueObject;

use App\Domain\Common\ValueObject\AbstractUnsignedInt;

final readonly class EventTargetId extends AbstractUnsignedInt
{
}
