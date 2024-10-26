<?php

declare(strict_types=1);

namespace App\Domain\Git\User\ValueObject;

use App\Domain\Common\ValueObject\AbstractRequiredUnsignedInt;

final readonly class UserId extends AbstractRequiredUnsignedInt
{
}
