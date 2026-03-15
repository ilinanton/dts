<?php

declare(strict_types=1);

namespace App\Infrastructure\Gitlab\Factory;

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
    /**
     * @param array{
     *     action?: string,
     *     commit_title?: string,
     *     commit_count?: int,
     *     commit_from?: string,
     *     commit_to?: string,
     *     ref?: string,
     *     ref_count?: int,
     *     ref_type?: string,
     * } $data
     */
    public function create(array $data): PushData
    {
        if ([] === $data) {
            return $this->createEmpty();
        }

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
