<?php

declare(strict_types=1);

namespace App\Domain\Common\ValueObject;

use DateTime;
use DateTimeZone;

abstract readonly class AbstractDate extends AbstractRequiredDate
{
    protected DateTime $value;
    protected string $format;
    private bool $isEmpty;
    public function __construct(string $value, string $format = DATE_RFC3339_EXTENDED)
    {
        if (strlen($value) > 0) {
            $this->format = $format;
            $this->assertValueIsValid($value);
            $this->value = DateTime::createFromFormat($this->format, $value);
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
        return $value === '' || $value === '0' ? null : $value;
    }
}
