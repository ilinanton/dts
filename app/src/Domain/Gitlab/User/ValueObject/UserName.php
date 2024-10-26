<?php

declare(strict_types=1);

namespace App\Domain\Gitlab\User\ValueObject;

use App\Domain\Common\ValueObject\AbstractRequiredString;

final readonly class UserName extends AbstractRequiredString
{
}
