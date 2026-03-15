<?php

declare(strict_types=1);

namespace App\Infrastructure\Gitlab;

use App\Domain\Common\ValueObject\AbstractDate;
use App\Domain\Common\ValueObject\AbstractString;
use App\Domain\Common\ValueObject\AbstractUnsignedInt;
use DateTimeZone;

final class DbValueConverter
{
    public static function nullableInt(AbstractUnsignedInt $vo): ?int
    {
        return $vo->value === 0 ? null : $vo->value;
    }

    public static function nullableString(AbstractString $vo): ?string
    {
        return $vo->value === '' || $vo->value === '0' ? null : $vo->value;
    }

    public static function nullableDate(
        AbstractDate $vo,
        DateTimeZone $timeZone = new DateTimeZone('Etc/GMT+0'),
    ): ?string {
        $value = $vo->getValue($timeZone);

        return $value === '' ? null : $value;
    }
}
