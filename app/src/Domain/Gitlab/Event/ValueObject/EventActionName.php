<?php

declare(strict_types=1);

namespace App\Domain\Gitlab\Event\ValueObject;

use App\Domain\Common\ValueObject\AbstractRequiredString;

final readonly class EventActionName extends AbstractRequiredString
{
}
