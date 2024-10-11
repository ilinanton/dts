<?php

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
use App\Domain\Gitlab\MergeRequest\ValueObject\MergeRequestWebUrl;

final readonly class MergeRequest
{
    public MergeRequestId $id;
    public MergeRequestIid $iid;
    public MergeRequestProjectId $projectId;
    public MergeRequestTitle $title;
    public MergeRequestState $state;
    public MergeRequestMergedAt $mergedAt;
    public MergeRequestCreatedAt $createdAt;
    public MergeRequestUpdatedAt $updatedAt;
    public MergeRequestTargetBranch $targetBranch;
    public MergeRequestSourceBranch $sourceBranch;
    public MergeRequestAuthorId $authorId;
    public MergeRequestWebUrl $webUrl;

    public function __construct(
        MergeRequestId $id,
        MergeRequestIid $iid,
        MergeRequestProjectId $projectId,
        MergeRequestTitle $title,
        MergeRequestState $state,
        MergeRequestMergedAt $mergedAt,
        MergeRequestCreatedAt $createdAt,
        MergeRequestUpdatedAt $updatedAt,
        MergeRequestTargetBranch $targetBranch,
        MergeRequestSourceBranch $sourceBranch,
        MergeRequestAuthorId $authorId,
        MergeRequestWebUrl $webUrl
    ) {
        $this->id = $id;
        $this->iid = $iid;
        $this->projectId = $projectId;
        $this->title = $title;
        $this->state = $state;
        $this->mergedAt = $mergedAt;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->targetBranch = $targetBranch;
        $this->sourceBranch = $sourceBranch;
        $this->authorId = $authorId;
        $this->webUrl = $webUrl;
    }
}
