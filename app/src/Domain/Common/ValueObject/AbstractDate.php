<?php

declare(strict_types=1);

namespace App\Domain\Common\ValueObject;

use DateTime;
use DateTimeZone;
use InvalidArgumentException;

abstract readonly class AbstractDate
{
    private ?DateTime $value;
    private string $format;

    public function __construct(string $value, string $format = DATE_RFC3339_EXTENDED)
    {
        $this->format = $format;

        if ($value === '') {
            $this->value = null;
            return;
        }

        $this->assertValueIsValid($value);
        $this->value = DateTime::createFromFormat($this->format, $value);
    }

    public function getValue(DateTimeZone $timeZone = new DateTimeZone('Etc/GMT+0')): string
    {
        if (!$this->value instanceof DateTime) {
            return '';
        }

        $this->value->setTimezone($timeZone);

        return $this->value->format('Y-m-d H:i:s');
    }

    public function getDbValue(DateTimeZone $timeZone = new DateTimeZone('Etc/GMT+0')): ?string
    {
        if (!$this->value instanceof DateTime) {
            return null;
        }

        return $this->getValue($timeZone);
    }

    private function assertValueIsValid(string $value): void
    {
        $dateTime = DateTime::createFromFormat($this->format, $value);
        if ($dateTime === false) {
            throw new InvalidArgumentException(get_class($this) . ' is incorrect!');
        }
    }
}
