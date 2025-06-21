<?php

declare(strict_types=1);

namespace App\Domain\Common\ValueObject;

use DateTime;
use DateTimeZone;
use InvalidArgumentException;

abstract readonly class AbstractRequiredDate
{
    protected DateTime $value;
    protected string $format;

    public function __construct(string $value, string $format = DATE_RFC3339_EXTENDED)
    {
        $this->format = $format;
        $this->assertValueIsValid($value);
        $this->value = DateTime::createFromFormat($this->format, $value);
    }

    public function getValue(DateTimeZone $timeZone = new DateTimeZone('Etc/GMT+0')): string
    {
        $this->value->setTimezone($timeZone);
        return $this->value->format('Y-m-d H:i:s');
    }

    public function getValueInMainFormat(): string
    {
        return $this->value->format($this->format);
    }

    protected function assertValueIsValid(string $value): void
    {
        $dateTime = DateTime::createFromFormat($this->format, $value);
        if (false === $dateTime) {
            throw new InvalidArgumentException(get_class($this) . ' is incorrect!');
        }
    }
}
