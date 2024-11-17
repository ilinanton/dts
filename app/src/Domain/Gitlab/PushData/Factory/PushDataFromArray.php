<?php

declare(strict_types=1);

namespace App\Domain\Gitlab\PushData\Factory;

use App\Domain\Gitlab\PushData\PushData;
use App\Domain\Gitlab\PushData\ValueObject\PushDataAction;
use App\Domain\Gitlab\PushData\ValueObject\PushDataCommitCount;
use App\Domain\Gitlab\PushData\ValueObject\PushDataCommitFrom;
use App\Domain\Gitlab\PushData\ValueObject\PushDataCommitTitle;
use App\Domain\Gitlab\PushData\ValueObject\PushDataCommitTo;
use App\Domain\Gitlab\PushData\ValueObject\PushDataRef;
use App\Domain\Gitlab\PushData\ValueObject\PushDataRefCount;
use App\Domain\Gitlab\PushData\ValueObject\PushDataRefType;

final readonly class PushDataFromArray
{
    public function __construct(
        private array $data,
    ) {
    }

    public function create(): PushData
    {
        if ([] === $this->data) {
            return $this->createEmpty();
        }

        return new PushData(
            new PushDataAction($this->data['action']),
            new PushDataCommitTitle($this->data['commit_title'] ?? ''),
            new PushDataCommitCount($this->data['commit_count']),
            new PushDataCommitFrom($this->data['commit_from'] ?? ''),
            new PushDataCommitTo($this->data['commit_to'] ?? ''),
            new PushDataRef($this->data['ref']),
            new PushDataRefCount($this->data['ref_count'] ?? 0),
            new PushDataRefType($this->data['ref_type']),
        );
    }

    private function createEmpty(): PushData
    {
        return new PushData(
            new PushDataAction(''),
            new PushDataCommitTitle(''),
            new PushDataCommitCount(0),
            new PushDataCommitFrom(''),
            new PushDataCommitTo(''),
            new PushDataRef(''),
            new PushDataRefCount(0),
            new PushDataRefType(''),
        );
    }
}
