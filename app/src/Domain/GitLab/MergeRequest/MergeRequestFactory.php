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

final class MergeRequestFactory
{
    public function create(array $data): MergeRequest
    {
        return new MergeRequest(
            new MergeRequestId($data['id'] ?? 0),
            new MergeRequestIid($data['iid'] ?? 0),
            new ProjectId($data['project_id'] ?? 0),
            new MergeRequestTitle($data['title'] ?? ''),
            new MergeRequestState($data['state'] ?? ''),
            new MergeRequestMergeUserId($data['merge_user_id'] ?? 0),
            new MergeRequestMergedAt($data['merged_at'] ?? ''),
            new MergeRequestCreatedAt($data['created_at'] ?? ''),
            new MergeRequestUpdatedAt($data['updated_at'] ?? ''),
            new MergeRequestTargetBranch($data['target_branch'] ?? ''),
            new MergeRequestSourceBranch($data['source_branch'] ?? ''),
            new MergeRequestAuthorId($data['author']['id'] ?? 0),
            new MergeRequestWebUrl($data['web_url'] ?? '')
        );
    }
}
