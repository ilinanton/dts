<?php

declare(strict_types=1);

namespace App\Domain\Gitlab\Common;

use App\Domain\Common\ValueObject\AbstractRequiredUnsignedInt;
use InvalidArgumentException;

final readonly class ItemsPerPage extends AbstractRequiredUnsignedInt
{
    public function __construct(int $value)
    {
        if ($value <= 0) {
            throw new InvalidArgumentException(self::class . ' must be greater than zero!');
        }
        parent::__construct($value);
    }
}
