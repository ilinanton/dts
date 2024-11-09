<?php

declare(strict_types=1);

namespace App\Domain\Gitlab\Event\ValueObject;

use App\Domain\Gitlab\PushData\PushData;
use App\Domain\Gitlab\PushData\PushDataFactory;

final readonly class EventPushData
{
    public PushData $value;

    public function __construct(array $value)
    {
        $pushDataFactory = new PushDataFactory();
        if (empty($value)) {
            $this->value = $pushDataFactory->createEmpty();
        } else {
            $this->value = $pushDataFactory->create($value);
        }
    }
}
