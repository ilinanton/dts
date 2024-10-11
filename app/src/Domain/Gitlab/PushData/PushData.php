<?php

namespace App\Domain\Gitlab\PushData;

use App\Domain\Gitlab\PushData\ValueObject\PushDataAction;
use App\Domain\Gitlab\PushData\ValueObject\PushDataCommitCount;
use App\Domain\Gitlab\PushData\ValueObject\PushDataCommitFrom;
use App\Domain\Gitlab\PushData\ValueObject\PushDataCommitTitle;
use App\Domain\Gitlab\PushData\ValueObject\PushDataCommitTo;
use App\Domain\Gitlab\PushData\ValueObject\PushDataRef;
use App\Domain\Gitlab\PushData\ValueObject\PushDataRefCount;
use App\Domain\Gitlab\PushData\ValueObject\PushDataRefType;

final readonly class PushData
{
    public function __construct(
        public PushDataAction $action,
        public PushDataCommitTitle $commitTitle,
        public PushDataCommitCount $commitCount,
        public PushDataCommitFrom $commitFrom,
        public PushDataCommitTo $commitTo,
        public PushDataRef $ref,
        public PushDataRefCount $refCount,
        public PushDataRefType $refType,
    ) {
    }
}
