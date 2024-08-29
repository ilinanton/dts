<?php

namespace App\Domain\Gitlab\Commit;

use App\Domain\Common\AbstractEntity;
use App\Domain\Gitlab\Commit\ValueObject\CommitAuthoredDate;
use App\Domain\Gitlab\Commit\ValueObject\CommitAuthorEmail;
use App\Domain\Gitlab\Commit\ValueObject\CommitAuthorName;
use App\Domain\Gitlab\Commit\ValueObject\CommitCommittedDate;
use App\Domain\Gitlab\Commit\ValueObject\CommitCommitterEmail;
use App\Domain\Gitlab\Commit\ValueObject\CommitCommitterName;
use App\Domain\Gitlab\Commit\ValueObject\CommitCreatedAt;
use App\Domain\Gitlab\Commit\ValueObject\CommitId;
use App\Domain\Gitlab\Commit\ValueObject\CommitShortId;
use App\Domain\Gitlab\Commit\ValueObject\CommitStats;
use App\Domain\Gitlab\Commit\ValueObject\CommitTitle;
use App\Domain\Gitlab\Commit\ValueObject\CommitWebUrl;

final readonly class Commit extends AbstractEntity
{
    public function __construct(
        private CommitId $id,
        private CommitShortId $shortId,
        private CommitTitle $title,
        private CommitCreatedAt $createdAt,
        private CommitWebUrl $webUrl,
        private CommitAuthorName $authorName,
        private CommitAuthorEmail $authorEmail,
        private CommitAuthoredDate $authoredDate,
        private CommitCommitterName $committerName,
        private CommitCommitterEmail $committerEmail,
        private CommitCommittedDate $committedDate,
        private CommitStats $stats,
    ) {
    }

    public function getId(): CommitId
    {
        return $this->id;
    }

    public function getShortId(): CommitShortId
    {
        return $this->shortId;
    }

    public function getTitle(): CommitTitle
    {
        return $this->title;
    }

    public function getCreatedAt(): CommitCreatedAt
    {
        return $this->createdAt;
    }

    public function getWebUrl(): CommitWebUrl
    {
        return $this->webUrl;
    }

    public function getAuthorName(): CommitAuthorName
    {
        return $this->authorName;
    }

    public function getAuthorEmail(): CommitAuthorEmail
    {
        return $this->authorEmail;
    }

    public function getAuthoredDate(): CommitAuthoredDate
    {
        return $this->authoredDate;
    }

    public function getCommitterName(): CommitCommitterName
    {
        return $this->committerName;
    }

    public function getCommitterEmail(): CommitCommitterEmail
    {
        return $this->committerEmail;
    }

    public function getCommittedDate(): CommitCommittedDate
    {
        return $this->committedDate;
    }

    public function getStats(): CommitStats
    {
        return $this->stats;
    }
}
