<?php

namespace App\Domain\GitLab\MergeRequest;

use App\Domain\GitLab\MergeRequest\ValueObject\MergeRequestAuthorId;
use App\Domain\GitLab\MergeRequest\ValueObject\MergeRequestCreatedAt;
use App\Domain\GitLab\MergeRequest\ValueObject\MergeRequestId;
use App\Domain\GitLab\MergeRequest\ValueObject\MergeRequestIid;
use App\Domain\GitLab\MergeRequest\ValueObject\MergeRequestMergedAt;
use App\Domain\GitLab\MergeRequest\ValueObject\MergeRequestMergeUserId;
use App\Domain\GitLab\MergeRequest\ValueObject\MergeRequestSourceBranch;
use App\Domain\GitLab\MergeRequest\ValueObject\MergeRequestState;
use App\Domain\GitLab\MergeRequest\ValueObject\MergeRequestTargetBranch;
use App\Domain\GitLab\MergeRequest\ValueObject\MergeRequestTitle;
use App\Domain\GitLab\MergeRequest\ValueObject\MergeRequestUpdatedAt;
use App\Domain\GitLab\MergeRequest\ValueObject\MergeRequestWebUrl;
use App\Domain\GitLab\Project\ValueObject\ProjectId;

final class MergeRequest
{
    private MergeRequestId $id;
    private MergeRequestIid $iid;
    private ProjectId $projectId;
    private MergeRequestTitle $title;
    private MergeRequestState $state;
    private MergeRequestMergeUserId $mergeUserId;
    private MergeRequestMergedAt $mergedAt;
    private MergeRequestCreatedAt $createdAt;
    private MergeRequestUpdatedAt $updatedAt;
    private MergeRequestTargetBranch $targetBranch;
    private MergeRequestSourceBranch $sourceBranch;
    private MergeRequestAuthorId $authorId;
    private MergeRequestWebUrl $webUrl;

    public function __construct(
        MergeRequestId $id,
        MergeRequestIid $iid,
        ProjectId $projectId,
        MergeRequestTitle $title,
        MergeRequestState $state,
        MergeRequestMergeUserId $mergeUserId,
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
        $this->mergeUserId = $mergeUserId;
        $this->mergedAt = $mergedAt;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->targetBranch = $targetBranch;
        $this->sourceBranch = $sourceBranch;
        $this->authorId = $authorId;
        $this->webUrl = $webUrl;
    }
}
