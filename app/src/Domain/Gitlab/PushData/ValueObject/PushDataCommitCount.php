<?php

declare(strict_types=1);

namespace App\Domain\Gitlab\PushData\ValueObject;

use App\Domain\Common\ValueObject\AbstractUnsignedInt;

final readonly class PushDataCommitCount extends AbstractUnsignedInt
{
}
