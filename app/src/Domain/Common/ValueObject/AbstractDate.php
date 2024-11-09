<?php

declare(strict_types=1);

namespace App\Domain\Common\ValueObject;

use DateTime;
use DateTimeZone;

readonly class AbstractDate extends AbstractRequiredDate
{
    protected DateTime $value;
    protected string $format;
    private bool $isEmpty;
    public function __construct(string $value, string $format = DATE_RFC3339_EXTENDED)
    {
        if (strlen($value) > 0) {
            parent::__construct($value, $format);
            $this->isEmpty = false;
        } else {
            $this->isEmpty = true;
            $this->format = $format;
            $this->value = new DateTime();
        }
    }

    public function getValue(DateTimeZone $timeZone = new DateTimeZone('Etc/GMT+0')): string
    {
        if ($this->isEmpty) {
            return '';
        }
        return parent::getValue($timeZone);
    }

    public function getDbValue(DateTimeZone $timeZone = new DateTimeZone('Etc/GMT+0')): ?string
    {
        $value = $this->getValue($timeZone);
        return empty($value) ? null : $value;
    }
}
