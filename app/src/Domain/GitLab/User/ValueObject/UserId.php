<?php

namespace App\Domain\GitLab\User\ValueObject;

use App\Domain\GitLab\Common\ValueObject\AbstractRequiredUnsignedInt;

final readonly class UserId extends AbstractRequiredUnsignedInt
{
}
