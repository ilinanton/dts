<?php

namespace App\Domain\Common\ValueObject;

use DateTime;

readonly class AbstractDate extends AbstractRequiredDate
{
    protected DateTime $value;
    protected string $format;
    public function __construct(string $value, string $format = DATE_RFC3339_EXTENDED)
    {
        if (strlen($value) > 0) {
            parent::__construct($value, $format);
        } else {
            $this->format = $format;
            $this->value = new DateTime();
        }
    }
}
