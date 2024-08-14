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

final readonly class PushData
{
    public function __construct(
        private PushDataAction $action,
        private PushDataCommitTitle $commitTitle,
        private PushDataCommitCount $commitCount,
        private PushDataCommitFrom $commitFrom,
        private PushDataCommitTo $commitTo,
        private PushDataRef $ref,
        private PushDataRefCount $refCount,
        private PushDataRefType $refType,
    ) {
    }

    public function getAction(): PushDataAction
    {
        return $this->action;
    }

    public function getCommitTitle(): PushDataCommitTitle
    {
        return $this->commitTitle;
    }

    public function getCommitCount(): PushDataCommitCount
    {
        return $this->commitCount;
    }

    public function getCommitFrom(): PushDataCommitFrom
    {
        return $this->commitFrom;
    }

    public function getCommitTo(): PushDataCommitTo
    {
        return $this->commitTo;
    }

    public function getRef(): PushDataRef
    {
        return $this->ref;
    }

    public function getRefCount(): PushDataRefCount
    {
        return $this->refCount;
    }

    public function getRefType(): PushDataRefType
    {
        return $this->refType;
    }
}
