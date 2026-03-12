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

final class MergeRequestFactory
{
    /**
     * @param array{
     *     id: int,
     *     iid: int,
     *     project_id: int,
     *     title: string,
     *     state: string,
     *     merged_at?: string,
     *     created_at: string,
     *     updated_at?: string,
     *     target_branch: string,
     *     source_branch: string,
     *     author_id: int,
     *     web_url: string,
     * } $data
     */
    public function create(array $data, string $dateFormat = DATE_RFC3339_EXTENDED): MergeRequest
    {
        return new MergeRequest(
            new MergeRequestId($data['id']),
            new MergeRequestIid($data['iid']),
            new MergeRequestProjectId($data['project_id']),
            new MergeRequestTitle($data['title']),
            MergeRequestState::from($data['state']),
            new MergeRequestMergedAt($data['merged_at'] ?? '', $dateFormat),
            new MergeRequestCreatedAt($data['created_at'], $dateFormat),
            new MergeRequestUpdatedAt($data['updated_at'] ?? '', $dateFormat),
            new MergeRequestTargetBranch($data['target_branch']),
            new MergeRequestSourceBranch($data['source_branch']),
            new MergeRequestAuthorId($data['author_id']),
            new MergeRequestRequiredWebUrl($data['web_url'])
        );
    }
}
