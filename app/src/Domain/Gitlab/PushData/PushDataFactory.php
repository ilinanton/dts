<?php

declare(strict_types=1);

namespace App\Domain\Gitlab\PushData;

use App\Domain\Gitlab\PushData\ValueObject\PushDataAction;
use App\Domain\Gitlab\PushData\ValueObject\PushDataCommitCount;
use App\Domain\Gitlab\PushData\ValueObject\PushDataCommitFrom;
use App\Domain\Gitlab\PushData\ValueObject\PushDataCommitTitle;
use App\Domain\Gitlab\PushData\ValueObject\PushDataCommitTo;
use App\Domain\Gitlab\PushData\ValueObject\PushDataRef;
use App\Domain\Gitlab\PushData\ValueObject\PushDataRefCount;
use App\Domain\Gitlab\PushData\ValueObject\PushDataRefType;

final class PushDataFactory
{
    public function create(array $data): PushData
    {
        return new PushData(
            new PushDataAction($data['action']),
            new PushDataCommitTitle($data['commit_title'] ?? ''),
            new PushDataCommitCount($data['commit_count']),
            new PushDataCommitFrom($data['commit_from'] ?? ''),
            new PushDataCommitTo($data['commit_to'] ?? ''),
            new PushDataRef($data['ref']),
            new PushDataRefCount($data['ref_count'] ?? 0),
            new PushDataRefType($data['ref_type']),
        );
    }

    public function createEmpty(): PushData
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
