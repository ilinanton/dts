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
use App\Domain\Gitlab\MergeRequest\ValueObject\MergeRequestWebUrl;

final class MergeRequestFactory
{
    public function create(array $data): MergeRequest
    {
        return new MergeRequest(
            new MergeRequestId($data['id'] ?? 0),
            new MergeRequestIid($data['iid'] ?? 0),
            new MergeRequestProjectId($data['project_id'] ?? 0),
            new MergeRequestTitle($data['title'] ?? ''),
            new MergeRequestState($data['state'] ?? ''),
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
