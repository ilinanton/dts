<?php

declare(strict_types=1);

namespace App\Domain\Gitlab\MergeRequest;

use App\Domain\Gitlab\MergeRequest\ValueObject\MergeRequestAuthorId;
use App\Domain\Gitlab\MergeRequest\ValueObject\MergeRequestCreatedAt;
use App\Domain\Gitlab\MergeRequest\ValueObject\MergeRequestId;
use App\Domain\Gitlab\MergeRequest\ValueObject\MergeRequestIid;
use App\Domain\Gitlab\MergeRequest\ValueObject\MergeRequestMergedAt;
use App\Domain\Gitlab\MergeRequest\ValueObject\MergeRequestProjectId;
use App\Domain\Gitlab\MergeRequest\ValueObject\MergeRequestSourceBranch;
use App\Domain\Gitlab\MergeRequest\ValueObject\MergeRequestState;
use App\Domain\Gitlab\MergeRequest\ValueObject\MergeRequestTargetBranch;
use App\Domain\Gitlab\MergeRequest\ValueObject\MergeRequestTitle;
use App\Domain\Gitlab\MergeRequest\ValueObject\MergeRequestUpdatedAt;
use App\Domain\Gitlab\MergeRequest\ValueObject\MergeRequestRequiredWebUrl;

final readonly class MergeRequest
{
    public function __construct(
        public MergeRequestId $id,
        public MergeRequestIid $iid,
        public MergeRequestProjectId $projectId,
        public MergeRequestTitle $title,
        public MergeRequestState $state,
        public MergeRequestMergedAt $mergedAt,
        public MergeRequestCreatedAt $createdAt,
        public MergeRequestUpdatedAt $updatedAt,
        public MergeRequestTargetBranch $targetBranch,
        public MergeRequestSourceBranch $sourceBranch,
        public MergeRequestAuthorId $authorId,
        public MergeRequestRequiredWebUrl $webUrl,
    ) {
    }
}
