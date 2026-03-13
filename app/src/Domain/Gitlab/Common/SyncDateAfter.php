<?php

declare(strict_types=1);

namespace App\Domain\Gitlab\Common;

use App\Domain\Common\ValueObject\AbstractRequiredDate;

final readonly class SyncDateAfter extends AbstractRequiredDate
{
    public function __construct(string $value)
    {
        parent::__construct($value, 'Y-m-d');
    }
}
