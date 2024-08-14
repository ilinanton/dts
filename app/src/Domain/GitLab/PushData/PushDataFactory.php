<?php

namespace App\Domain\GitLab\PushData;

use App\Domain\GitLab\PushData\ValueObject\PushDataAction;
use App\Domain\GitLab\PushData\ValueObject\PushDataCommitCount;
use App\Domain\GitLab\PushData\ValueObject\PushDataCommitFrom;
use App\Domain\GitLab\PushData\ValueObject\PushDataCommitTitle;
use App\Domain\GitLab\PushData\ValueObject\PushDataCommitTo;
use App\Domain\GitLab\PushData\ValueObject\PushDataRef;
use App\Domain\GitLab\PushData\ValueObject\PushDataRefCount;
use App\Domain\GitLab\PushData\ValueObject\PushDataRefType;

final class PushDataFactory
{
    public function create(array $data): PushData
    {
        return new PushData(
            new PushDataAction($data['action'] ?? ''),
            new PushDataCommitTitle($data['commit_title'] ?? ''),
            new PushDataCommitCount($data['commit_count'] ?? 0),
            new PushDataCommitFrom($data['commit_from'] ?? ''),
            new PushDataCommitTo($data['commit_to'] ?? ''),
            new PushDataRef($data['ref'] ?? ''),
            new PushDataRefCount($data['ref_count'] ?? 0),
            new PushDataRefType($data['ref_type'] ?? ''),
        );
    }
}
