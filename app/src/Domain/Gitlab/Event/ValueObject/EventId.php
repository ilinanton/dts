<?php

namespace App\Domain\Gitlab\Event\ValueObject;

use App\Domain\Common\ValueObject\AbstractRequiredUnsignedInt;

final readonly class EventId extends AbstractRequiredUnsignedInt
{
}
